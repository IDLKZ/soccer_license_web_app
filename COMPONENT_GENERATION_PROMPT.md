# Laravel Livewire Component Generation Prompt

## Project Context
Система управления лицензиями на фут клубы на Laravel 12.0 с Livewire 3, поддержкой трех языков (русский, казахский, английский) и темной темой на Tailwind CSS.

---

## COMPONENT STRUCTURE STANDARDS

### 1. Livewire Component Class Structure

#### Required Imports
```php
<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads; // Только если нужны файлы
```

#### Class Declaration Pattern
```php
#[Title('Заголовок страницы')]
class ComponentName extends Component
{
    use WithPagination;
    use WithFileUploads; // Опционально

    protected $paginationTheme = 'tailwind';
}
```

#### Property Organization
```php
// 1. Modal States
public $showCreateModal = false;
public $showEditModal = false;
public $editingItemId = null;

// 2. Search & Filters
public $search = '';
public $filterField1 = '';
public $filterField2 = '';

// 3. Form Data (с валидацией через атрибуты)
#[Validate('required|string|max:100')]
public $fieldNameRu = '';

#[Validate('required|string|max:100')]
public $fieldNameKk = '';

#[Validate('required|string|max:100')]
public $fieldNameEn = '';

// 4. Relationships Data
public $cities = [];
public $types = [];

// 5. Permissions (Locked)
#[Locked]
public $canCreate = false;

#[Locked]
public $canEdit = false;

#[Locked]
public $canDelete = false;
```

#### Validation Patterns
```php
// Multilingual fields (required)
#[Validate('required|string|max:255')]
public $titleRu = '';

#[Validate('required|string|max:255')]
public $titleKk = '';

#[Validate('required|string|max:255')]
public $titleEn = '';

// Multilingual descriptions (optional)
#[Validate('nullable|string')]
public $descriptionRu = '';

#[Validate('nullable|string')]
public $descriptionKk = '';

#[Validate('nullable|string')]
public $descriptionEn = '';

// Foreign keys
#[Validate('required|integer|exists:cities,id')]
public $cityId = '';

// Image uploads
#[Validate('nullable|image|max:5120')] // max 5MB
public $image = null;

// Boolean fields
public $isActive = true;

// JSON arrays
#[Validate('nullable|array')]
public $phone = [];
```

#### Mount Method Pattern
```php
public function mount()
{
    // 1. Authorization
    $this->authorize('permission-name');

    // 2. Set permissions
    $user = auth()->user();
    $this->canCreate = $user->can('create-permission');
    $this->canEdit = $user->can('manage-permission');
    $this->canDelete = $user->can('delete-permission');

    // 3. Load relationship data
    $this->loadCities();
    $this->loadTypes();
}
```

#### Updater Methods for Filters
```php
public function updatedSearch()
{
    $this->resetPage();
}

public function updatedFilterCity()
{
    $this->resetPage();
}
```

#### Query Methods Pattern
```php
public function getItems()
{
    $query = Model::with(['relation1', 'relation2']);

    // Search
    if ($this->search) {
        $query->where(function($q) {
            $q->where('field_ru', 'like', '%' . $this->search . '%')
              ->orWhere('field_kk', 'like', '%' . $this->search . '%')
              ->orWhere('field_en', 'like', '%' . $this->search . '%');
        });
    }

    // Filters
    if (!empty($this->filterCity)) {
        $query->where('city_id', $this->filterCity);
    }

    if ($this->filterStatus !== '' && $this->filterStatus !== null) {
        $query->where('is_active', $this->filterStatus === '1');
    }

    return $query->orderBy('created_at', 'desc')->paginate(10);
}
```

#### CRUD Methods Pattern
```php
public function createItem()
{
    $this->authorize('create-permission');

    $this->validate([
        'titleRu' => 'required|string|max:255',
        'titleKk' => 'required|string|max:255',
        'titleEn' => 'required|string|max:255',
        // ... other validation
    ]);

    $item = Model::create([
        'title_ru' => $this->titleRu,
        'title_kk' => $this->titleKk,
        'title_en' => $this->titleEn,
        'is_active' => (bool) $this->isActive,
    ]);

    // Handle media if needed
    if ($this->image) {
        $media = $item->addMedia($this->image->getRealPath())
              ->usingName($this->image->getClientOriginalName())
              ->usingFileName($this->image->getClientOriginalName())
              ->toMediaCollection('image');

        $item->update(['image_url' => $media->getUrl()]);
    }

    $this->reset(['titleRu', 'titleKk', 'titleEn', 'showCreateModal']);
    session()->flash('message', 'Запись успешно создана');
    $this->render();
}

public function editItem($itemId)
{
    $item = Model::findOrFail($itemId);
    $this->authorize('manage-permission');

    $this->editingItemId = $item->id;
    $this->titleRu = $item->title_ru;
    $this->titleKk = $item->title_kk;
    $this->titleEn = $item->title_en;
    $this->isActive = $item->is_active;

    $this->showEditModal = true;
}

public function updateItem()
{
    $this->authorize('manage-permission');

    $item = Model::findOrFail($this->editingItemId);

    $this->validate([
        'titleRu' => 'required|string|max:255',
        'titleKk' => 'required|string|max:255',
        'titleEn' => 'required|string|max:255',
    ]);

    $item->update([
        'title_ru' => $this->titleRu,
        'title_kk' => $this->titleKk,
        'title_en' => $this->titleEn,
        'is_active' => (bool) $this->isActive,
    ]);

    $this->reset(['titleRu', 'titleKk', 'titleEn', 'showEditModal', 'editingItemId']);
    session()->flash('message', 'Запись успешно обновлена');
    $this->render();
}

public function deleteItem($itemId)
{
    $this->authorize('delete-permission');

    $item = Model::findOrFail($itemId);

    // Check relationships
    if ($item->children()->count() > 0) {
        session()->flash('error', 'Нельзя удалить запись с дочерними элементами');
        return;
    }

    $item->media()->delete();
    $item->delete();

    session()->flash('message', 'Запись успешно удалена');
}

public function toggleItemStatus($itemId)
{
    $this->authorize('manage-permission');

    $item = Model::findOrFail($itemId);
    $item->is_active = !$item->is_active;
    $item->save();

    session()->flash('message', 'Статус изменен');
}
```

#### Render Method Pattern
```php
public function render()
{
    return view('livewire.component-name', [
        'items' => $this->getItems(),
    ])->layout(get_user_layout());
}
```

---

## 2. BLADE VIEW STRUCTURE

### Container & Header
```blade
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Заголовок</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Описание</p>
        </div>
        @if($canCreate)
        <button wire:click="$set('showCreateModal', true)"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-semibold rounded-lg transition-colors duration-150">
            <i class="fas fa-plus mr-2"></i>
            Создать
        </button>
        @endif
    </div>
</div>
```

### Flash Messages
```blade
<!-- Success Messages -->
@if(session()->has('message'))
<div class="mb-4 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 dark:border-green-400 p-4 rounded">
    <div class="flex">
        <i class="fas fa-check-circle text-green-500 dark:text-green-400 mt-0.5"></i>
        <p class="ml-3 text-green-700 dark:text-green-300">{{ session('message') }}</p>
    </div>
</div>
@endif

<!-- Error Messages -->
@if(session()->has('error'))
<div class="mb-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 dark:border-red-400 p-4 rounded">
    <div class="flex">
        <i class="fas fa-exclamation-circle text-red-500 dark:text-red-400 mt-0.5"></i>
        <p class="ml-3 text-red-700 dark:text-red-300">{{ session('error') }}</p>
    </div>
</div>
@endif
```

### Search & Filters Section
```blade
<!-- Поиск и фильтры -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md dark:shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                <i class="fas fa-search mr-1 text-gray-400 dark:text-gray-500"></i>
                Поиск
            </label>
            <input type="text"
                   wire:model.live.debounce.500ms="search"
                   placeholder="Поиск..."
                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                <i class="fas fa-filter mr-1 text-gray-400 dark:text-gray-500"></i>
                Фильтр
            </label>
            <select wire:model.live="filterCity"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors">
                <option value="">Все</option>
                @foreach($cities as $city)
                <option value="{{ $city->id }}">{{ $city->title_ru }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
```

### Data Table
```blade
@if($items->count() > 0)
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg dark:shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                        <div class="flex items-center">
                            <i class="fas fa-tag mr-1 text-gray-400 dark:text-gray-500"></i>
                            Название
                        </div>
                    </th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                        <div class="flex items-center justify-center">
                            <i class="fas fa-toggle-on mr-1 text-gray-400 dark:text-gray-500"></i>
                            Статус
                        </div>
                    </th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                        <div class="flex items-center justify-center">
                            <i class="fas fa-cogs mr-1 text-gray-400 dark:text-gray-500"></i>
                            Действия
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($items as $item)
                <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 dark:hover:from-blue-900/20 dark:hover:to-indigo-900/30 transition-all duration-150 border-l-4 border-transparent hover:border-blue-400 dark:hover:border-blue-500">
                    <td class="px-4 py-4">
                        <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                            {{ $item->title_ru }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $item->title_kk }} / {{ $item->title_en }}
                        </div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-center">
                        @if($item->is_active)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-700">
                            <i class="fas fa-check-circle mr-1 text-green-600 dark:text-green-400"></i>
                            Активен
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-red-100 to-pink-100 dark:from-red-900/30 dark:to-pink-900/30 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-700">
                            <i class="fas fa-times-circle mr-1 text-red-600 dark:text-red-400"></i>
                            Неактивен
                        </span>
                        @endif
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-center">
                        <div class="flex items-center justify-center gap-2">
                            @if($canEdit)
                            <button wire:click="editItem({{ $item->id }})"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/20 dark:hover:bg-indigo-900/30 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-150"
                                    title="Редактировать">
                                <i class="fas fa-edit text-sm"></i>
                            </button>
                            @endif
                            @if($canDelete)
                            <button wire:click="deleteItem({{ $item->id }})"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/30 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-150"
                                    title="Удалить"
                                    onclick="return confirm('Вы уверены?')">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Пагинация -->
@if($items->hasPages())
<div class="mt-8">
    {{ $items->links('pagination::tailwind') }}
</div>
@endif
@else
<div class="text-center py-12">
    <div class="flex flex-col items-center">
        <i class="fas fa-inbox text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
        <p class="text-gray-500 dark:text-gray-400 font-medium">Записи не найдены</p>
        <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Попробуйте изменить параметры фильтрации</p>
    </div>
</div>
@endif
```

### Modal Structure (Create/Edit)
```blade
@if($showCreateModal)
<div wire:ignore.self class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" wire:click="$set('showCreateModal', false)">
            <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-200 dark:border-gray-700">
            <form wire:submit.prevent="createItem">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[90vh] overflow-y-auto">
                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Создание записи</h3>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <!-- Form Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Название (RU)*
                                </label>
                                <input type="text"
                                       wire:model="titleRu"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                @error('titleRu') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Название (KK)*
                                </label>
                                <input type="text"
                                       wire:model="titleKk"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                @error('titleKk') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Название (EN)*
                                </label>
                                <input type="text"
                                       wire:model="titleEn"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                @error('titleEn') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Text Editor for Descriptions -->
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Описание на русском
                                </label>
                                <x-text-editor
                                    name="descriptionRu"
                                    placeholder="Введите описание на русском..."
                                    height="150px"
                                />
                                @error('descriptionRu') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Статус*</label>
                            <select wire:model="isActive"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                <option value="1">Активен</option>
                                <option value="0">Неактивен</option>
                            </select>
                            @error('isActive') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 dark:bg-blue-500 text-base font-medium text-white hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-blue-400 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Создать
                    </button>
                    <button type="button"
                            wire:click="$set('showCreateModal', false)"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Отмена
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
```

---

## 3. KEY PATTERNS & CONVENTIONS

### Multilingual Fields Naming
- **Database columns**: `field_ru`, `field_kk`, `field_en`
- **Component properties**: `fieldRu`, `fieldKk`, `fieldEn`
- **Always validate all three languages for required fields**

### Database Field Patterns
```php
// String fields with multilingual support
'title_ru', 'title_kk', 'title_en'           // Required titles
'short_name_ru', 'short_name_kk', 'short_name_en'  // Short names
'full_name_ru', 'full_name_kk', 'full_name_en'     // Full names
'description_ru', 'description_kk', 'description_en' // Descriptions (nullable)
'address_ru', 'address_kk', 'address_en'      // Addresses (nullable)

// Common fields
'is_active' => 'boolean'                      // Status flag
'phone' => 'json'                            // JSON array for phones
'image_url' => 'string|nullable'             // Image path
'deleted_at'                                 // Soft delete timestamp
```

### Authorization Pattern
```php
// Mount method
$this->authorize('view-permission');

// CRUD operations
$this->authorize('create-permission');
$this->authorize('manage-permission'); // for edit
$this->authorize('delete-permission');

// Permission checks for UI
$this->canCreate = $user->can('create-permission');
$this->canEdit = $user->can('manage-permission');
$this->canDelete = $user->can('delete-permission');
```

### Media Library Pattern (Spatie)
```php
// Model must implement HasMedia
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Model extends Model implements HasMedia
{
    use InteractsWithMedia;
}

// Upload in component
if ($this->image) {
    $media = $model->addMedia($this->image->getRealPath())
          ->usingName($this->image->getClientOriginalName())
          ->usingFileName($this->image->getClientOriginalName())
          ->toMediaCollection('image');

    $model->update(['image_url' => $media->getUrl()]);
}

// Display in Blade
@if($item->getFirstMediaUrl('image'))
    <img src="{{ $item->getFirstMediaUrl('image', 'thumb') }}" alt="{{ $item->title_ru }}">
@endif

// Delete media
$model->clearMediaCollection('image');
$model->media()->delete();
```

### Status Badge Colors
```blade
<!-- Active/Success -->
bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30
text-green-800 dark:text-green-300 border border-green-200 dark:border-green-700

<!-- Inactive/Error -->
bg-gradient-to-r from-red-100 to-pink-100 dark:from-red-900/30 dark:to-pink-900/30
text-red-800 dark:text-red-300 border border-red-200 dark:border-red-700

<!-- Warning/Pending -->
bg-gradient-to-r from-yellow-100 to-orange-100 dark:from-yellow-900/30 dark:to-orange-900/30
text-yellow-800 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-700

<!-- Info/Neutral -->
bg-gradient-to-r from-blue-100 to-cyan-100 dark:from-blue-900/30 dark:to-cyan-900/30
text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-700

<!-- Purple/Special -->
bg-gradient-to-r from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30
text-indigo-800 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-700
```

### Button Styles
```blade
<!-- Primary Action -->
bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white

<!-- Edit -->
bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/20 dark:hover:bg-indigo-900/30
text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300

<!-- Delete -->
bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/30
text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300

<!-- Toggle Status -->
bg-yellow-50 hover:bg-yellow-100 dark:bg-yellow-900/20 dark:hover:bg-yellow-900/30
text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300
```

### Input Field Classes (Dark Mode Support)
```blade
<!-- Text Input -->
class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors"

<!-- Select -->
class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors"

<!-- Textarea -->
class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 resize-none transition-colors"
```

---

## 4. COMPONENT TYPES

### Type A: Simple Dictionary/Reference Data (CRUD)
**Примеры**: Countries, Cities, Judge Types, Transport Types, Seasons

**Характеристики**:
- Простые CRUD операции
- Multilingual fields (title_ru, title_kk, title_en)
- Optional description fields
- Status flag (is_active)
- No complex relationships
- Standard search & filters

**Шаблон структуры**:
```php
// Properties
public $titleRu = '';
public $titleKk = '';
public $titleEn = '';
public $descriptionRu = '';
public $descriptionKk = '';
public $descriptionEn = '';
public $isActive = true;

// Methods: createItem(), editItem(), updateItem(), deleteItem(), toggleItemStatus()
```

### Type B: Complex Entity Management
**Примеры**: Clubs, Stadiums, Hotels

**Характеристики**:
- Multiple relationships (belongsTo, hasMany)
- Image uploads with Spatie Media Library
- JSON fields (phone arrays)
- Advanced filtering
- Hierarchical structures (parent-child)
- Multiple validation rules

**Дополнительные property**:
```php
public $parentId = '';      // For hierarchical
public $cityId = '';        // Foreign keys
public $image = null;       // File uploads
public $phone = [];         // JSON arrays
public $website = '';       // URLs
public $bin = '';          // Business IDs
```

### Type C: Card-Based Workflow Components
**Примеры**: MatchAssignmentCards, BusinessTripCards, RefereeTeamApprovalCards

**Характеристики**:
- Tab-based navigation
- Card display instead of tables
- Workflow state management
- Modal-based detail views
- Status-based filtering by operations
- Action buttons for workflow transitions

**Шаблон структуры**:
```php
public $activeTab = 'tab1';
public $selectedItem = null;
public $showDetailModal = false;

public function switchTab($tab) {
    $this->activeTab = $tab;
}

public function openDetail($itemId) {
    $this->selectedItem = Model::with(['relations'])->findOrFail($itemId);
    $this->showDetailModal = true;
}
```

### Type D: Detail/Read-Only Views
**Примеры**: MatchAssignmentDetail, BusinessTripDetail, RefereeTeamApprovalDetail

**Характеристики**:
- Display-only, no editing
- Detailed information layout
- Relationships loading
- Document/file display
- Timeline/history views
- Status indicators

---

## 5. COMMON COMPONENTS & HELPERS

### Text Editor Component
```blade
<x-text-editor
    name="descriptionRu"
    placeholder="Введите описание..."
    height="150px"
/>
```

### Layout Helper
```php
// Always use in render method
return view('livewire.component-name')->layout(get_user_layout());
```

### Icons (Font Awesome)
- `fa-plus` - Create
- `fa-edit` - Edit
- `fa-trash` - Delete
- `fa-search` - Search
- `fa-filter` - Filter
- `fa-check-circle` - Active/Success
- `fa-times-circle` - Inactive/Error
- `fa-toggle-on` - Status toggle
- `fa-cogs` - Actions/Settings
- `fa-eye` - View details
- `fa-ban` - Deactivate
- `fa-check` - Activate

---

## 6. GENERATION CHECKLIST

При создании нового компонента проверь:

### Backend (Livewire Component)
- [ ] Правильные импорты и namespace
- [ ] Title атрибут на классе
- [ ] Traits: WithPagination, WithFileUploads (если нужно)
- [ ] Все properties с правильными Validate атрибутами
- [ ] Locked атрибуты для permissions
- [ ] mount() с authorization и загрузкой данных
- [ ] Методы updatedFilter*() для сброса пагинации
- [ ] Query метод с поиском и фильтрами
- [ ] CRUD методы с authorization
- [ ] Правильный render() с get_user_layout()

### Frontend (Blade View)
- [ ] Container и header с кнопкой создания
- [ ] Flash messages для success и error
- [ ] Секция поиска и фильтров
- [ ] Таблица с данными или карточки
- [ ] Пагинация с проверкой hasPages()
- [ ] Empty state когда нет данных
- [ ] Модалки Create и Edit с формами
- [ ] Все inputs с dark mode классами
- [ ] Icons для всех элементов
- [ ] Validation errors под каждым полем
- [ ] Правильные wire:model директивы

### Multilingual Support
- [ ] Все текстовые поля в трех вариантах (_ru, _kk, _en)
- [ ] Validation для всех языковых вариантов
- [ ] Display всех языков в таблицах/карточках
- [ ] Descriptions через text-editor компонент

### Dark Mode
- [ ] Все цвета с dark: вариантами
- [ ] Backgrounds: dark:bg-gray-800
- [ ] Text: dark:text-gray-100
- [ ] Borders: dark:border-gray-700
- [ ] Inputs: dark:bg-gray-700 dark:text-gray-100
- [ ] Focus states с dark: вариантами

### Authorization
- [ ] Gate checks в mount() и CRUD методах
- [ ] Permission-based UI элементы (@if($canCreate))
- [ ] Правильные имена permissions

### Media Handling (если нужно)
- [ ] Model implements HasMedia
- [ ] Trait InteractsWithMedia
- [ ] Upload logic в create/update методах
- [ ] Display logic в Blade
- [ ] Delete logic при удалении записи

---

## 7. EXAMPLE PROMPTS

### Для простого справочника:
```
Создай Livewire компонент для управления [Entity Name].
Поля: title (ru/kk/en), description (ru/kk/en), is_active.
Permissions: view-[entity], create-[entity], manage-[entity], delete-[entity].
Включи CRUD, поиск, фильтр по статусу, пагинацию.
```

### Для сложной сущности:
```
Создай Livewire компонент для управления [Entity Name].
Поля: [список полей с типами]
Relationships: belongsTo [Parent], hasMany [Children]
Включи upload изображения через Spatie Media Library.
Permissions: [список permissions]
Фильтры: [список фильтров]
```

### Для workflow компонента:
```
Создай Livewire компонент с карточками для [Workflow Name].
Tabs: [tab1], [tab2], [tab3]
Каждый tab показывает записи с operation=[operation_value]
Включи modal для просмотра деталей и кнопки для смены статуса.
```

---

## 8. COMMON MISTAKES TO AVOID

1. **Забыть dark mode классы** - всегда добавляй dark: варианты
2. **Не сбрасывать пагинацию** - добавляй updatedFilter*() методы
3. **Пропустить authorization** - проверяй permissions везде
4. **Не закрывать модалки** - используй wire:ignore.self
5. **Забыть о soft deletes** - проверяй deleted_at в моделях
6. **Не валидировать все языки** - ru, kk, en для required полей
7. **Пропустить media cleanup** - удаляй media при удалении записи
8. **Не использовать get_user_layout()** - всегда в render()
9. **Забыть про icons** - FontAwesome icons везде для UX
10. **Игнорировать empty states** - показывай сообщение когда нет данных

---

## 9. NAMING CONVENTIONS

### Files
- Component class: `app/Livewire/EntityManagement.php`
- View file: `resources/views/livewire/entity-management.blade.php`
- Model: `app/Models/Entity.php`

### Properties
- camelCase для свойств: `titleRu`, `shortNameEn`
- snake_case для database columns: `title_ru`, `short_name_en`

### Methods
- CRUD: `createItem()`, `editItem()`, `updateItem()`, `deleteItem()`
- Loaders: `loadCities()`, `loadTypes()`
- Toggles: `toggleItemStatus()`
- Queries: `getItems()`
- Modals: `openDetail()`, `closeDetail()`

### Permissions
- Pattern: `{action}-{entity-plural}`
- Examples: `view-clubs`, `create-clubs`, `manage-clubs`, `delete-clubs`

---

**При генерации следуй этому prompt строго, адаптируя под конкретную задачу!**
