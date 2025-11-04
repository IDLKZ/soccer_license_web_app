<?php

namespace App\Livewire\Admin;

use App\Models\CategoryDocument;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Title('Управление документами')]
class DocumentManagement extends Component
{
    use WithFileUploads, WithPagination;

    protected $paginationTheme = 'tailwind';

    // Modal States
    public $showCreateModal = false;

    public $showEditModal = false;

    public $editingDocumentId = null;

    // Search & Filters
    public $search = '';

    public $filterCategory = '';

    public $filterLevel = '';

    // Form Data
    #[Validate('required|string|max:255')]
    public $titleRu = '';

    #[Validate('required|string|max:255')]
    public $titleKk = '';

    #[Validate('nullable|string|max:255')]
    public $titleEn = '';

    #[Validate('nullable|string|max:1000')]
    public $descriptionRu = '';

    #[Validate('nullable|string|max:1000')]
    public $descriptionKk = '';

    #[Validate('nullable|string|max:1000')]
    public $descriptionEn = '';

    #[Validate('nullable|integer|exists:category_documents,id')]
    public $categoryId = null;

    #[Validate('required|integer|min:1|max:10')]
    public $level = 1;

    #[Validate('nullable|file|max:10240')] // max 10MB
    public $exampleFile;

    public $currentExampleFile = null;

    // Relationships Data
    public $categories = [];

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
        $this->authorize('view-documents');

        // Set permissions
        $user = auth()->user();
        $this->canCreate = $user ? $user->can('create-documents') : false;
        $this->canEdit = $user ? $user->can('manage-documents') : false;
        $this->canDelete = $user ? $user->can('delete-documents') : false;

        // Load relationship data
        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = CategoryDocument::orderBy('level')->orderBy('title_ru')->get();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterCategory()
    {
        $this->resetPage();
    }

    public function updatedFilterLevel()
    {
        $this->resetPage();
    }

    public function getDocuments()
    {
        $query = Document::with('category_document');

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title_ru', 'like', '%'.$this->search.'%')
                    ->orWhere('title_kk', 'like', '%'.$this->search.'%')
                    ->orWhere('title_en', 'like', '%'.$this->search.'%')
                    ->orWhere('description_ru', 'like', '%'.$this->search.'%')
                    ->orWhere('value', 'like', '%'.$this->search.'%');
            });
        }

        // Filters
        if ($this->filterCategory !== '' && $this->filterCategory !== null) {
            $query->where('category_id', $this->filterCategory);
        }

        if ($this->filterLevel !== '' && $this->filterLevel !== null) {
            $query->where('level', $this->filterLevel);
        }

        return $query->orderBy('level', 'asc')
            ->orderBy('title_ru', 'asc')
            ->paginate(20);
    }

    // Method to handle pagination properly
    public function gotoPage($page)
    {
        $this->setPage($page);
    }

    public function createDocument()
    {
        $this->authorize('create-documents');

        $this->validate([
            'titleRu' => 'required|string|max:255',
            'titleKk' => 'required|string|max:255',
            'level' => 'required|integer|min:1|max:10',
            'exampleFile' => 'nullable|file|max:10240',
        ]);

        $exampleFileUrl = null;

        // Handle file upload
        if ($this->exampleFile) {
            $filename = time().'_'.$this->exampleFile->getClientOriginalName();
            $this->exampleFile->storeAs('uploads', $filename, 'public');
            $exampleFileUrl = 'uploads/'.$filename;
        }

        Document::create([
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
            'description_ru' => $this->descriptionRu,
            'description_kk' => $this->descriptionKk,
            'description_en' => $this->descriptionEn,
            'category_id' => $this->categoryId,
            'level' => $this->level,
            'example_file_url' => $exampleFileUrl,
        ]);

        $this->reset(['titleRu', 'titleKk', 'titleEn', 'descriptionRu', 'descriptionKk', 'descriptionEn', 'categoryId', 'level', 'exampleFile', 'showCreateModal']);
        session()->flash('message', 'Документ успешно создан');
    }

    public function editDocument($documentId)
    {
        $document = Document::findOrFail($documentId);
        $this->authorize('manage-documents');

        $this->editingDocumentId = $document->id;
        $this->titleRu = $document->title_ru;
        $this->titleKk = $document->title_kk ?? '';
        $this->titleEn = $document->title_en ?? '';
        $this->descriptionRu = $document->description_ru ?? '';
        $this->descriptionKk = $document->description_kk ?? '';
        $this->descriptionEn = $document->description_en ?? '';
        $this->categoryId = $document->category_id;
        $this->level = $document->level;
        $this->currentExampleFile = $document->example_file_url;
        $this->exampleFile = null;

        $this->showEditModal = true;
        $this->dispatch('openEditModal');
    }

    public function updateDocument()
    {
        $this->authorize('manage-documents');

        $document = Document::findOrFail($this->editingDocumentId);

        $this->validate([
            'titleRu' => 'required|string|max:255',
            'titleKk' => 'required|string|max:255',
            'level' => 'required|integer|min:1|max:10',
            'exampleFile' => 'nullable|file|max:10240',
        ]);

        $exampleFileUrl = $document->example_file_url;

        // Handle file upload
        if ($this->exampleFile) {
            // Delete old file if exists
            if ($document->example_file_url) {
                Storage::disk('public')->delete($document->example_file_url);
            }

            $filename = time().'_'.$this->exampleFile->getClientOriginalName();
            $this->exampleFile->storeAs('uploads', $filename, 'public');
            $exampleFileUrl = 'uploads/'.$filename;
        }

        $document->update([
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
            'description_ru' => $this->descriptionRu,
            'description_kk' => $this->descriptionKk,
            'description_en' => $this->descriptionEn,
            'category_id' => $this->categoryId,
            'level' => $this->level,
            'example_file_url' => $exampleFileUrl,
        ]);

        $this->reset(['titleRu', 'titleKk', 'titleEn', 'descriptionRu', 'descriptionKk', 'descriptionEn', 'categoryId', 'level', 'exampleFile', 'currentExampleFile', 'showEditModal', 'editingDocumentId']);
        session()->flash('message', 'Документ успешно обновлен');
    }

    public function deleteDocument($documentId)
    {
        $this->authorize('delete-documents');

        $document = Document::findOrFail($documentId);

        // Delete file if exists
        if ($document->example_file_url) {
            Storage::disk('public')->delete($document->example_file_url);
        }

        $document->delete();

        session()->flash('message', 'Документ успешно удален');
    }

    public function removeExampleFile()
    {
        if ($this->editingDocumentId) {
            $document = Document::findOrFail($this->editingDocumentId);

            if ($document->example_file_url) {
                Storage::disk('public')->delete($document->example_file_url);
                $document->update(['example_file_url' => null]);
                $this->currentExampleFile = null;
                session()->flash('message', 'Файл успешно удален');
            }
        }
    }

    public function openCreateModal()
    {
        $this->showCreateModal = true;
        $this->dispatch('openCreateModal');
    }

    public function closeCreateModal()
    {
        $this->dispatch('closeCreateModal');
        $this->showCreateModal = false;
        $this->reset(['titleRu', 'titleKk', 'titleEn', 'descriptionRu', 'descriptionKk', 'descriptionEn', 'categoryId', 'level', 'exampleFile']);
    }

    public function closeEditModal()
    {
        $this->dispatch('closeEditModal');
        $this->showEditModal = false;
        $this->reset(['titleRu', 'titleKk', 'titleEn', 'descriptionRu', 'descriptionKk', 'descriptionEn', 'categoryId', 'level', 'exampleFile', 'currentExampleFile', 'editingDocumentId']);
    }

    public function render()
    {
        return view('livewire.admin.document-management', [
            'documents' => $this->getDocuments(),
        ])->layout(get_user_layout());
    }
}
