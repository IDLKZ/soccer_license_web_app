<?php

namespace App\Livewire\Admin;

use App\Models\CategoryDocument;
use App\Models\Document;
use App\Models\Licence;
use App\Models\LicenceRequirement;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Управление требованиями к лицензиям')]
class LicenceRequirementManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // Modal States
    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingRequirementId = null;

    // Search & Filters
    public $search = '';
    public $filterLicence = '';
    public $filterCategory = '';
    public $filterRequired = '';

    // Form Data
    #[Validate('required|integer|exists:licences,id')]
    public $licenceId = '';

    #[Validate('nullable|integer|exists:category_documents,id')]
    public $categoryId = '';

    #[Validate('nullable|integer|exists:documents,id')]
    public $documentId = '';

    #[Validate('required|boolean')]
    public $isRequired = true;

    public $allowedExtensions = [];

    #[Validate('required|numeric|min:0.1')]
    public $maxFileSizeMb = 10;

    // Relationships Data
    public $licences = [];
    public $categories = [];
    public $documents = [];

    // Permissions
    #[Locked]
    public $canCreate = false;

    #[Locked]
    public $canEdit = false;

    #[Locked]
    public $canDelete = false;

    public function mount()
    {
        // Authorization
        // $this->authorize('view-licence-requirements');

        // Set permissions
        $user = auth()->user();
        $this->canCreate = $user ? $user->can('create-licence-requirements') : false;
        $this->canEdit = $user ? $user->can('manage-licence-requirements') : false;
        $this->canDelete = $user ? $user->can('delete-licence-requirements') : false;

        // Load relationship data
        $this->loadRelationships();
    }

    public function loadRelationships()
    {
        $this->licences = Licence::with(['season', 'league'])
            ->orderBy('created_at', 'desc')
            ->get();
        $this->categories = CategoryDocument::orderBy('title_ru')->get();
        $this->documents = Document::orderBy('title_ru')->get();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterLicence()
    {
        $this->resetPage();
    }

    public function updatedFilterCategory()
    {
        $this->resetPage();
    }

    public function updatedFilterRequired()
    {
        $this->resetPage();
    }

    // Get documents filtered by category
    public function getDocumentsByCategory($categoryId)
    {
        if (empty($categoryId)) {
            return Document::orderBy('title_ru')->get();
        }
        return Document::where('category_id', $categoryId)->orderBy('title_ru')->get();
    }

    // When category changes, reset document selection
    public function updatedCategoryId()
    {
        $this->documentId = '';
        $this->documents = $this->getDocumentsByCategory($this->categoryId);
    }

    public function getRequirements()
    {
        $query = LicenceRequirement::with(['licence.season', 'licence.league', 'category_document', 'document']);

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('document', function ($dq) {
                    $dq->where('title_ru', 'like', '%' . $this->search . '%')
                        ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                        ->orWhere('title_en', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('category_document', function ($cq) {
                    $cq->where('title_ru', 'like', '%' . $this->search . '%')
                        ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                        ->orWhere('title_en', 'like', '%' . $this->search . '%');
                });
            });
        }

        // Filter by licence
        if (!empty($this->filterLicence)) {
            $query->where('licence_id', $this->filterLicence);
        }

        // Filter by category
        if (!empty($this->filterCategory)) {
            $query->where('category_id', $this->filterCategory);
        }

        // Filter by required status
        if ($this->filterRequired !== '' && $this->filterRequired !== null) {
            $query->where('is_required', $this->filterRequired === '1');
        }

        return $query->orderBy('created_at', 'desc')->paginate(20);
    }

    public function gotoPage($page)
    {
        $this->setPage($page);
    }

    // Normalize allowedExtensions to ensure it's always an array
    private function normalizeAllowedExtensions()
    {
        if (is_string($this->allowedExtensions)) {
            $this->allowedExtensions = json_decode($this->allowedExtensions, true) ?? [];
        }
        if (!is_array($this->allowedExtensions)) {
            $this->allowedExtensions = [];
        }
    }

    public function createRequirement()
    {
        $this->authorize('create-licence-requirements');

        // Ensure allowedExtensions is an array
        $this->normalizeAllowedExtensions();

        $this->validate([
            'licenceId' => 'required|integer|exists:licences,id',
            'categoryId' => 'nullable|integer|exists:category_documents,id',
            'documentId' => 'nullable|integer|exists:documents,id',
            'isRequired' => 'required|boolean',
            'allowedExtensions' => 'nullable|array',
            'maxFileSizeMb' => 'required|numeric|min:0.1',
        ]);

        LicenceRequirement::create([
            'licence_id' => $this->licenceId,
            'category_id' => $this->categoryId ?: null,
            'document_id' => $this->documentId ?: null,
            'is_required' => $this->isRequired,
            'allowed_extensions' => !empty($this->allowedExtensions) ? $this->allowedExtensions : null,
            'max_file_size_mb' => $this->maxFileSizeMb,
        ]);

        $this->resetForm();
        session()->flash('message', 'Требование успешно создано');
    }

    public function editRequirement($requirementId)
    {
        $requirement = LicenceRequirement::findOrFail($requirementId);
        $this->authorize('manage-licence-requirements');

        $this->editingRequirementId = $requirement->id;
        $this->licenceId = $requirement->licence_id;
        $this->categoryId = $requirement->category_id ?? '';
        $this->documentId = $requirement->document_id ?? '';
        $this->isRequired = $requirement->is_required;
        $this->allowedExtensions = $requirement->allowed_extensions ?? [];
        $this->maxFileSizeMb = $requirement->max_file_size_mb;

        // Load documents for selected category
        if ($this->categoryId) {
            $this->documents = $this->getDocumentsByCategory($this->categoryId);
        }

        $this->showEditModal = true;
    }

    public function updateRequirement()
    {
        $this->authorize('manage-licence-requirements');

        $requirement = LicenceRequirement::findOrFail($this->editingRequirementId);

        // Ensure allowedExtensions is an array
        $this->normalizeAllowedExtensions();

        $this->validate([
            'licenceId' => 'required|integer|exists:licences,id',
            'categoryId' => 'nullable|integer|exists:category_documents,id',
            'documentId' => 'nullable|integer|exists:documents,id',
            'isRequired' => 'required|boolean',
            'allowedExtensions' => 'nullable|array',
            'maxFileSizeMb' => 'required|numeric|min:0.1',
        ]);

        $requirement->update([
            'licence_id' => $this->licenceId,
            'category_id' => $this->categoryId ?: null,
            'document_id' => $this->documentId ?: null,
            'is_required' => $this->isRequired,
            'allowed_extensions' => !empty($this->allowedExtensions) ? $this->allowedExtensions : null,
            'max_file_size_mb' => $this->maxFileSizeMb,
        ]);

        $this->resetForm();
        session()->flash('message', 'Требование успешно обновлено');
    }

    public function deleteRequirement($requirementId)
    {
        $this->authorize('delete-licence-requirements');

        $requirement = LicenceRequirement::findOrFail($requirementId);
        $requirement->delete();

        session()->flash('message', 'Требование успешно удалено');
    }

    public function closeCreateModal()
    {
        $this->resetForm();
    }

    public function closeEditModal()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->reset([
            'editingRequirementId',
            'licenceId',
            'categoryId',
            'documentId',
            'isRequired',
            'allowedExtensions',
            'maxFileSizeMb'
        ]);
        $this->isRequired = true;
        $this->maxFileSizeMb = 10;
        $this->allowedExtensions = [];

        // Reload all documents
        $this->documents = Document::orderBy('title_ru')->get();
    }

    public function render()
    {
        return view('livewire.admin.licence-requirement-management', [
            'requirements' => $this->getRequirements(),
        ])->layout(get_user_layout());
    }
}
