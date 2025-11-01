<?php

namespace App\Livewire\Admin;

use App\Models\ApplicationStatusCategory;
use App\Models\Role;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Управление категориями статусов заявок')]
class ApplicationStatusCategoryManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // Modal States
    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingCategoryId = null;

    // Search & Filters
    public $search = '';
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
    public $catPreviousId = null;

    #[Validate('nullable|integer|exists:application_status_categories,id')]
    public $catNextId = null;

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
        $this->authorize('view-application-status-categories');

        // Set permissions
        $user = auth()->user();
        $this->canCreate = $user ? $user->can('create-application-status-categories') : false;
        $this->canEdit = $user ? $user->can('manage-application-status-categories') : false;
        $this->canDelete = $user ? $user->can('delete-application-status-categories') : false;

        // Load relationship data
        $this->loadRoles();
        $this->loadCategories();
    }

    public function loadRoles()
    {
        $this->availableRoles = Role::orderBy('title_ru')->get();
    }

    public function loadCategories()
    {
        $this->availableCategories = ApplicationStatusCategory::orderBy('title_ru')->get();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterResult()
    {
        $this->resetPage();
    }

    public function getCategories()
    {
        $query = ApplicationStatusCategory::query();

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('title_en', 'like', '%' . $this->search . '%')
                  ->orWhere('value', 'like', '%' . $this->search . '%');
            });
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

    public function createCategory()
    {
        $this->authorize('create-application-status-categories');

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

        ApplicationStatusCategory::create([
            'cat_previous_id' => $this->catPreviousId,
            'cat_next_id' => $this->catNextId,
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

        $this->reset(['titleRu', 'titleKk', 'titleEn', 'descriptionRu', 'descriptionKk', 'descriptionEn', 'catPreviousId', 'catNextId', 'isActive', 'isFirst', 'isLast', 'result', 'selectedRoles', 'showCreateModal']);
        $this->isActive = true;
        $this->isFirst = false;
        $this->isLast = false;
        $this->result = 0;
        $this->loadCategories();
        session()->flash('message', 'Категория статуса успешно создана');
    }

    public function editCategory($categoryId)
    {
        $category = ApplicationStatusCategory::findOrFail($categoryId);
        $this->authorize('manage-application-status-categories');

        $this->editingCategoryId = $category->id;
        $this->titleRu = $category->title_ru;
        $this->titleKk = $category->title_kk ?? '';
        $this->titleEn = $category->title_en ?? '';
        $this->descriptionRu = $category->description_ru ?? '';
        $this->descriptionKk = $category->description_kk ?? '';
        $this->descriptionEn = $category->description_en ?? '';
        $this->catPreviousId = $category->cat_previous_id;
        $this->catNextId = $category->cat_next_id;
        $this->isActive = $category->is_active;
        $this->isFirst = $category->is_first;
        $this->isLast = $category->is_last;
        $this->result = $category->result;

        // Convert role values (slugs) back to IDs for checkboxes
        $this->selectedRoles = [];
        if (!empty($category->role_values)) {
            $this->selectedRoles = $this->availableRoles
                ->whereIn('value', $category->role_values)
                ->pluck('id')
                ->toArray();
        }

        $this->showEditModal = true;
    }

    public function updateCategory()
    {
        $this->authorize('manage-application-status-categories');

        $category = ApplicationStatusCategory::findOrFail($this->editingCategoryId);

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

        $category->update([
            'cat_previous_id' => $this->catPreviousId,
            'cat_next_id' => $this->catNextId,
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

        $this->reset(['titleRu', 'titleKk', 'titleEn', 'descriptionRu', 'descriptionKk', 'descriptionEn', 'catPreviousId', 'catNextId', 'isActive', 'isFirst', 'isLast', 'result', 'selectedRoles', 'showEditModal', 'editingCategoryId']);
        $this->isActive = true;
        $this->isFirst = false;
        $this->isLast = false;
        $this->result = 0;
        $this->loadCategories();
        session()->flash('message', 'Категория статуса успешно обновлена');
    }

    public function deleteCategory($categoryId)
    {
        $this->authorize('delete-application-status-categories');

        $category = ApplicationStatusCategory::findOrFail($categoryId);
        $category->delete();

        $this->loadCategories();
        session()->flash('message', 'Категория статуса успешно удалена');
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->reset(['titleRu', 'titleKk', 'titleEn', 'descriptionRu', 'descriptionKk', 'descriptionEn', 'catPreviousId', 'catNextId', 'isActive', 'isFirst', 'isLast', 'result', 'selectedRoles']);
        $this->isActive = true;
        $this->isFirst = false;
        $this->isLast = false;
        $this->result = 0;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->reset(['titleRu', 'titleKk', 'titleEn', 'descriptionRu', 'descriptionKk', 'descriptionEn', 'catPreviousId', 'catNextId', 'isActive', 'isFirst', 'isLast', 'result', 'selectedRoles', 'editingCategoryId']);
        $this->isActive = true;
        $this->isFirst = false;
        $this->isLast = false;
        $this->result = 0;
    }

    public function render()
    {
        return view('livewire.admin.application-status-category-management', [
            'categories' => $this->getCategories(),
        ])->layout(get_user_layout());
    }
}
