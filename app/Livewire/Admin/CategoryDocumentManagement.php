<?php

namespace App\Livewire\Admin;

use App\Models\CategoryDocument;
use App\Models\Role;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Управление категориями документов')]
class CategoryDocumentManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // Modal States
    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingCategoryId = null;

    // Search & Filters
    public $search = '';
    public $filterLevel = '';

    // Form Data
    #[Validate('required|string|max:255')]
    public $titleRu = '';

    #[Validate('required|string|max:255')]
    public $titleKk = '';

    #[Validate('nullable|string|max:255')]
    public $titleEn = '';

    #[Validate('required|integer|min:1|max:10')]
    public $level = 1;

    public $selectedRoles = [];

    // Relationships Data
    public $availableRoles = [];

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
        $this->authorize('view-category-documents');

        // Set permissions
        $user = auth()->user();
        $this->canCreate = $user ? $user->can('create-category-documents') : false;
        $this->canEdit = $user ? $user->can('manage-category-documents') : false;
        $this->canDelete = $user ? $user->can('delete-category-documents') : false;

        // Load relationship data
        $this->loadRoles();
    }

    public function loadRoles()
    {
        $this->availableRoles = Role::orderBy('title_ru')->get();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterLevel()
    {
        $this->resetPage();
    }

    public function getCategories()
    {
        $query = CategoryDocument::query();

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('title_en', 'like', '%' . $this->search . '%')
                  ->orWhere('value', 'like', '%' . $this->search . '%');
            });
        }

        // Filters
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

    public function createCategory()
    {
        $this->authorize('create-category-documents');

        $this->validate([
            'titleRu' => 'required|string|max:255',
            'titleKk' => 'required|string|max:255',
            'level' => 'required|integer|min:1|max:10',
        ]);

        // Convert selected role IDs to role values (slugs)
        $roleValues = null;
        if (!empty($this->selectedRoles)) {
            $roleValues = $this->availableRoles
                ->whereIn('id', $this->selectedRoles)
                ->pluck('value')
                ->toArray();
        }

        CategoryDocument::create([
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
            'level' => $this->level,
            'roles' => $roleValues,
        ]);

        $this->reset(['titleRu', 'titleKk', 'titleEn', 'level', 'selectedRoles', 'showCreateModal']);
        session()->flash('message', 'Категория документов успешно создана');
    }

    public function editCategory($categoryId)
    {
        $category = CategoryDocument::findOrFail($categoryId);
        $this->authorize('manage-category-documents');

        $this->editingCategoryId = $category->id;
        $this->titleRu = $category->title_ru;
        $this->titleKk = $category->title_kk ?? '';
        $this->titleEn = $category->title_en ?? '';
        $this->level = $category->level;

        // Convert role values (slugs) back to IDs for checkboxes
        $this->selectedRoles = [];
        if (!empty($category->roles)) {
            $this->selectedRoles = $this->availableRoles
                ->whereIn('value', $category->roles)
                ->pluck('id')
                ->toArray();
        }

        $this->showEditModal = true;
    }

    public function updateCategory()
    {
        $this->authorize('manage-category-documents');

        $category = CategoryDocument::findOrFail($this->editingCategoryId);

        $this->validate([
            'titleRu' => 'required|string|max:255',
            'titleKk' => 'required|string|max:255',
            'level' => 'required|integer|min:1|max:10',
        ]);

        // Convert selected role IDs to role values (slugs)
        $roleValues = null;
        if (!empty($this->selectedRoles)) {
            $roleValues = $this->availableRoles
                ->whereIn('id', $this->selectedRoles)
                ->pluck('value')
                ->toArray();
        }

        $categoryData = [
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
            'level' => $this->level,
            'roles' => $roleValues,
        ];

        $category->update($categoryData);

        $this->reset(['titleRu', 'titleKk', 'titleEn', 'level', 'selectedRoles', 'showEditModal', 'editingCategoryId']);
        session()->flash('message', 'Категория документов успешно обновлена');
    }

    public function deleteCategory($categoryId)
    {
        $this->authorize('delete-category-documents');

        $category = CategoryDocument::findOrFail($categoryId);

        // Prevent deleting categories with documents
        if ($category->documents()->count() > 0) {
            session()->flash('error', 'Нельзя удалить категорию, привязанную к документам');
            return;
        }

        $category->delete();

        session()->flash('message', 'Категория документов успешно удалена');
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->reset(['titleRu', 'titleKk', 'titleEn', 'level', 'selectedRoles']);
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->reset(['titleRu', 'titleKk', 'titleEn', 'level', 'selectedRoles', 'editingCategoryId']);
    }

    public function render()
    {
        return view('livewire.admin.category-document-management', [
            'categories' => $this->getCategories(),
        ])->layout(get_user_layout());
    }
}
