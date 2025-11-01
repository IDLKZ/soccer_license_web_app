<?php

namespace App\Livewire\Admin;

use App\Models\ApplicationStatus;
use App\Models\ApplicationStatusCategory;
use App\Models\Role;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Управление статусами заявок')]
class ApplicationStatusManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // Modal States
    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingStatusId = null;

    // Search & Filters
    public $search = '';
    public $filterCategory = '';
    public $filterResult = '';

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

    #[Validate('nullable|integer|exists:application_status_categories,id')]
    public $categoryId = null;

    #[Validate('nullable|integer|exists:application_statuses,id')]
    public $previousId = null;

    #[Validate('nullable|integer|exists:application_statuses,id')]
    public $nextId = null;

    #[Validate('required|boolean')]
    public $isActive = true;

    #[Validate('required|boolean')]
    public $isFirst = false;

    #[Validate('required|boolean')]
    public $isLast = false;

    #[Validate('required|integer|min:-1|max:1')]
    public $result = 0;

    public $selectedRoles = [];

    // Relationships Data
    public $availableRoles = [];
    public $availableCategories = [];
    public $availableStatuses = [];

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
        $this->authorize('view-application-statuses');

        // Set permissions
        $user = auth()->user();
        $this->canCreate = $user ? $user->can('create-application-statuses') : false;
        $this->canEdit = $user ? $user->can('manage-application-statuses') : false;
        $this->canDelete = $user ? $user->can('delete-application-statuses') : false;

        // Load relationship data
        $this->loadRoles();
        $this->loadCategories();
        $this->loadStatuses();
    }

    public function loadRoles()
    {
        $this->availableRoles = Role::orderBy('title_ru')->get();
    }

    public function loadCategories()
    {
        $this->availableCategories = ApplicationStatusCategory::orderBy('title_ru')->get();
    }

    public function loadStatuses()
    {
        $this->availableStatuses = ApplicationStatus::orderBy('title_ru')->get();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterCategory()
    {
        $this->resetPage();
    }

    public function updatedFilterResult()
    {
        $this->resetPage();
    }

    public function getStatuses()
    {
        $query = ApplicationStatus::with('application_status_category');

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('title_en', 'like', '%' . $this->search . '%')
                  ->orWhere('value', 'like', '%' . $this->search . '%');
            });
        }

        // Filter by category
        if ($this->filterCategory !== '' && $this->filterCategory !== null) {
            $query->where('category_id', $this->filterCategory);
        }

        // Filter by result
        if ($this->filterResult !== '' && $this->filterResult !== null) {
            $query->where('result', $this->filterResult);
        }

        return $query->orderBy('id', 'asc')->paginate(20);
    }

    // Method to handle pagination properly
    public function gotoPage($page)
    {
        $this->setPage($page);
    }

    public function createStatus()
    {
        $this->authorize('create-application-statuses');

        $this->validate([
            'titleRu' => 'required|string|max:255',
            'titleKk' => 'required|string|max:255',
            'isActive' => 'required|boolean',
            'isFirst' => 'required|boolean',
            'isLast' => 'required|boolean',
            'result' => 'required|integer|min:-1|max:1',
        ]);

        // Convert selected role IDs to role values (slugs)
        $roleValues = null;
        if (!empty($this->selectedRoles)) {
            $roleValues = $this->availableRoles
                ->whereIn('id', $this->selectedRoles)
                ->pluck('value')
                ->toArray();
        }

        ApplicationStatus::create([
            'category_id' => $this->categoryId,
            'previous_id' => $this->previousId,
            'next_id' => $this->nextId,
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
            'description_ru' => $this->descriptionRu,
            'description_kk' => $this->descriptionKk,
            'description_en' => $this->descriptionEn,
            'role_values' => $roleValues,
            'is_active' => $this->isActive,
            'is_first' => $this->isFirst,
            'is_last' => $this->isLast,
            'result' => $this->result,
        ]);

        $this->reset(['titleRu', 'titleKk', 'titleEn', 'descriptionRu', 'descriptionKk', 'descriptionEn', 'categoryId', 'previousId', 'nextId', 'isActive', 'isFirst', 'isLast', 'result', 'selectedRoles', 'showCreateModal']);
        $this->isActive = true;
        $this->isFirst = false;
        $this->isLast = false;
        $this->result = 0;
        $this->loadStatuses();
        session()->flash('message', 'Статус успешно создан');
    }

    public function editStatus($statusId)
    {
        $status = ApplicationStatus::findOrFail($statusId);
        $this->authorize('manage-application-statuses');

        $this->editingStatusId = $status->id;
        $this->titleRu = $status->title_ru;
        $this->titleKk = $status->title_kk ?? '';
        $this->titleEn = $status->title_en ?? '';
        $this->descriptionRu = $status->description_ru ?? '';
        $this->descriptionKk = $status->description_kk ?? '';
        $this->descriptionEn = $status->description_en ?? '';
        $this->categoryId = $status->category_id;
        $this->previousId = $status->previous_id;
        $this->nextId = $status->next_id;
        $this->isActive = $status->is_active;
        $this->isFirst = $status->is_first;
        $this->isLast = $status->is_last;
        $this->result = $status->result;

        // Convert role values (slugs) back to IDs for checkboxes
        $this->selectedRoles = [];
        if (!empty($status->role_values)) {
            $this->selectedRoles = $this->availableRoles
                ->whereIn('value', $status->role_values)
                ->pluck('id')
                ->toArray();
        }

        $this->showEditModal = true;
    }

    public function updateStatus()
    {
        $this->authorize('manage-application-statuses');

        $status = ApplicationStatus::findOrFail($this->editingStatusId);

        $this->validate([
            'titleRu' => 'required|string|max:255',
            'titleKk' => 'required|string|max:255',
            'isActive' => 'required|boolean',
            'isFirst' => 'required|boolean',
            'isLast' => 'required|boolean',
            'result' => 'required|integer|min:-1|max:1',
        ]);

        // Convert selected role IDs to role values (slugs)
        $roleValues = null;
        if (!empty($this->selectedRoles)) {
            $roleValues = $this->availableRoles
                ->whereIn('id', $this->selectedRoles)
                ->pluck('value')
                ->toArray();
        }

        $status->update([
            'category_id' => $this->categoryId,
            'previous_id' => $this->previousId,
            'next_id' => $this->nextId,
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
            'description_ru' => $this->descriptionRu,
            'description_kk' => $this->descriptionKk,
            'description_en' => $this->descriptionEn,
            'role_values' => $roleValues,
            'is_active' => $this->isActive,
            'is_first' => $this->isFirst,
            'is_last' => $this->isLast,
            'result' => $this->result,
        ]);

        $this->reset(['titleRu', 'titleKk', 'titleEn', 'descriptionRu', 'descriptionKk', 'descriptionEn', 'categoryId', 'previousId', 'nextId', 'isActive', 'isFirst', 'isLast', 'result', 'selectedRoles', 'showEditModal', 'editingStatusId']);
        $this->isActive = true;
        $this->isFirst = false;
        $this->isLast = false;
        $this->result = 0;
        $this->loadStatuses();
        session()->flash('message', 'Статус успешно обновлен');
    }

    public function deleteStatus($statusId)
    {
        $this->authorize('delete-application-statuses');

        $status = ApplicationStatus::findOrFail($statusId);
        $status->delete();

        $this->loadStatuses();
        session()->flash('message', 'Статус успешно удален');
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->reset(['titleRu', 'titleKk', 'titleEn', 'descriptionRu', 'descriptionKk', 'descriptionEn', 'categoryId', 'previousId', 'nextId', 'isActive', 'isFirst', 'isLast', 'result', 'selectedRoles']);
        $this->isActive = true;
        $this->isFirst = false;
        $this->isLast = false;
        $this->result = 0;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->reset(['titleRu', 'titleKk', 'titleEn', 'descriptionRu', 'descriptionKk', 'descriptionEn', 'categoryId', 'previousId', 'nextId', 'isActive', 'isFirst', 'isLast', 'result', 'selectedRoles', 'editingStatusId']);
        $this->isActive = true;
        $this->isFirst = false;
        $this->isLast = false;
        $this->result = 0;
    }

    public function render()
    {
        return view('livewire.admin.application-status-management', [
            'statuses' => $this->getStatuses(),
        ])->layout(get_user_layout());
    }
}
