<?php

namespace App\Livewire\Admin;

use App\Models\Licence;
use App\Models\LicenceRequirement;
use App\Models\LicenceDeadline;
use App\Models\Season;
use App\Models\League;
use App\Models\Club;
use App\Models\Document;
use App\Models\CategoryDocument;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

#[Title('Управление лицензиями')]
class LicenceManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // Modal States
    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingLicenceId = null;

    // Search & Filters
    public $search = '';
    public $filterSeasonId = '';
    public $filterLeagueId = '';

    // Form Data - Licence
    #[Validate('nullable|integer|exists:seasons,id')]
    public $seasonId = null;

    #[Validate('nullable|integer|exists:leagues,id')]
    public $leagueId = null;

    #[Validate('required|string|max:255')]
    public $titleRu = '';

    #[Validate('required|string|max:255')]
    public $titleKk = '';

    #[Validate('nullable|string|max:255')]
    public $titleEn = '';

    #[Validate('nullable|string')]
    public $descriptionRu = '';

    #[Validate('nullable|string')]
    public $descriptionKk = '';

    #[Validate('nullable|string')]
    public $descriptionEn = '';

    #[Validate('required|date')]
    public $startAt = '';

    #[Validate('required|date|after:start_at')]
    public $endAt = '';

    public $isActive = true;

    // Licence Requirements (dynamic fields)
    public $requirements = [];

    // Licence Deadlines (dynamic fields)
    public $deadlines = [];

    // Relationships Data
    public $seasons = [];
    public $leagues = [];
    public $clubs = [];
    public $documents = [];
    public $categories = [];

    // Permissions
    #[Locked]
    public $canCreate = false;

    #[Locked]
    public $canEdit = false;

    #[Locked]
    public $canDelete = false;

    /**
     * Override validate method to show validation errors via toastr
     */
    public function validate($rules = null, $messages = [], $attributes = [])
    {
        try {
            return parent::validate($rules, $messages, $attributes);
        } catch (ValidationException $e) {
            $this->showValidationErrorsViaToastr($e);
            throw $e;
        }
    }

    /**
     * Show validation errors via toastr with grouping
     */
    private function showValidationErrorsViaToastr(ValidationException $e)
    {
        $errors = $e->validator->errors();

        // Group errors by field
        $groupedErrors = [];
        $allErrors = $errors->all();

        if (count($allErrors) === 1) {
            // Single error - show directly
            $this->dispatch('showMessage', [
                'type' => 'error',
                'message' => $allErrors[0]
            ]);
        } else {
            // Multiple errors - show grouped
            $this->dispatch('showMessage', [
                'type' => 'error',
                'message' => 'Исправьте следующие ошибки (' . count($allErrors) . '):'
            ]);

            // Show individual errors with slight delay
            foreach ($allErrors as $index => $error) {
                $this->dispatch('showMessage', [
                    'type' => 'error',
                    'message' => '• ' . $error
                ]);
            }
        }
    }

    public function mount()
    {
        // Authorization
        $this->authorize('view-licences');

        // Set permissions
        $user = auth()->user();
        $this->canCreate = $user ? $user->can('create-licences') : false;
        $this->canEdit = $user ? $user->can('manage-licences') : false;
        $this->canDelete = $user ? $user->can('delete-licences') : false;

        // Load relationship data
        $this->loadSeasons();
        $this->loadLeagues();
        $this->loadClubs();
        $this->loadDocuments();
        $this->loadCategories();
    }

    public function loadSeasons()
    {
        $this->seasons = Season::orderBy('start', 'desc')->get();
    }

    public function loadLeagues()
    {
        $this->leagues = League::orderBy('title_ru')->get();
    }

    public function loadClubs()
    {
        $this->clubs = Club::orderBy('short_name_ru')->get();
    }

    public function loadDocuments()
    {
        $this->documents = Document::orderBy('title_ru')->get();
    }

    public function loadCategories()
    {
        $this->categories = CategoryDocument::orderBy('title_ru')->get();
    }

    // Get documents filtered by category for a specific requirement
    public function getDocumentsByCategory($categoryId)
    {
        if (empty($categoryId)) {
            return [];
        }
        return Document::where('category_id', $categoryId)->orderBy('title_ru')->get();
    }

    // When category changes, reset the document selection
    public function updatedRequirements($value, $name)
    {
        // Check if category_id was updated
        if (strpos($name, '.category_id') !== false) {
            // Extract the index from the name (e.g., "0.category_id" -> "0")
            $index = explode('.', $name)[0];
            // Reset document_id for this requirement
            $this->requirements[$index]['document_id'] = null;
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterSeasonId()
    {
        $this->resetPage();
    }

    public function updatedFilterLeagueId()
    {
        $this->resetPage();
    }

    public function getLicences()
    {
        $query = Licence::with(['season', 'league', 'licence_requirements', 'licence_deadlines']);

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('title_en', 'like', '%' . $this->search . '%');
            });
        }

        // Filter by season
        if ($this->filterSeasonId !== '' && $this->filterSeasonId !== null) {
            $query->where('season_id', $this->filterSeasonId);
        }

        // Filter by league
        if ($this->filterLeagueId !== '' && $this->filterLeagueId !== null) {
            $query->where('league_id', $this->filterLeagueId);
        }

        return $query->orderBy('created_at', 'desc')->paginate(20);
    }

    public function addRequirement()
    {
        $this->requirements[] = [
            'category_id' => null,
            'document_id' => null,
            'is_required' => true,
            'allowed_extensions' => ['pdf', 'doc', 'docx'], // Default extensions
            'max_file_size_mb' => 10,
        ];
    }

    public function removeRequirement($index)
    {
        unset($this->requirements[$index]);
        $this->requirements = array_values($this->requirements);
    }

    public function addDeadline()
    {
        $this->deadlines[] = [
            'club_id' => null,
            'start_at' => '',
            'end_at' => '',
        ];
    }

    public function removeDeadline($index)
    {
        unset($this->deadlines[$index]);
        $this->deadlines = array_values($this->deadlines);
    }

    public function createLicence()
    {
        $this->authorize('create-licences');

        // Custom validation for duplicate document_id within same licence
        $this->validate([
            'requirements' => 'required|array|min:1',
            'requirements.*.category_id' => 'required_without:requirements.*.document_id|integer|exists:category_documents,id',
            'requirements.*.document_id' => 'required_without:requirements.*.category_id|integer|exists:documents,id',
            'deadlines' => 'required|array|min:1',
            'deadlines.*.club_id' => 'required|integer|exists:clubs,id',
            'deadlines.*.start_at' => 'required|date',
            'deadlines.*.end_at' => 'required|date|after:deadlines.*.start_at',
        ], [
            'requirements.required' => 'Необходимо добавить хотя бы одно требование к лицензии',
            'requirements.min' => 'Необходимо добавить хотя бы одно требование к лицензии',
            'requirements.*.category_id.required_without' => 'Для каждого требования необходимо выбрать категорию или документ',
            'requirements.*.document_id.required_without' => 'Для каждого требования необходимо выбрать документ или категорию',
            'deadlines.required' => 'Необходимо добавить хотя бы один дедлайн для клуба',
            'deadlines.min' => 'Необходимо добавить хотя бы один дедлайн для клуба',
            'deadlines.*.club_id.required' => 'Для каждого дедлайна необходимо выбрать клуб',
            'deadlines.*.start_at.required' => 'Для каждого дедлайна необходимо указать дату начала',
            'deadlines.*.end_at.required' => 'Для каждого дедлайна необходимо указать дату окончания',
            'deadlines.*.end_at.after' => 'Дата окончания должна быть позже даты начала',
        ]);

        // Custom validation: Check for duplicate document_id within the same licence
        $documentIds = array_filter(array_column($this->requirements, 'document_id'));
        if (count($documentIds) !== count(array_unique($documentIds))) {
            // Find duplicates for specific error message
            $duplicates = array_diff_assoc($documentIds, array_unique($documentIds));
            $duplicateIds = array_values(array_unique($duplicates));

            // Get document names for better error message
            $documentNames = Document::whereIn('id', $duplicateIds)->pluck('title_ru')->toArray();
            $namesList = implode(', ', $documentNames);

            $this->addError('requirements', "Нельзя добавлять один и тот же документ несколько раз в рамках одной лицензии. Дублирующиеся документы: {$namesList}");

            // Show toastr notification
            $this->dispatch('showMessage', [
                'type' => 'error',
                'message' => "Нельзя добавлять один и тот же документ несколько раз в рамках одной лицензии. Дублирующиеся документы: {$namesList}"
            ]);

            return;
        }

        // Validate remaining fields
        try {
            $this->validate();
        } catch (ValidationException $e) {
            // Errors are already shown via toastr in the overridden validate method
            return;
        }

        $licence = Licence::create([
            'season_id' => $this->seasonId,
            'league_id' => $this->leagueId,
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
            'description_ru' => $this->descriptionRu,
            'description_kk' => $this->descriptionKk,
            'description_en' => $this->descriptionEn,
            'start_at' => $this->startAt,
            'end_at' => $this->endAt,
            'is_active' => $this->isActive,
        ]);

        // Create requirements (now mandatory)
        foreach ($this->requirements as $req) {
            LicenceRequirement::create([
                'licence_id' => $licence->id,
                'category_id' => $req['category_id'],
                'document_id' => $req['document_id'],
                'is_required' => $req['is_required'],
                'allowed_extensions' => !empty($req['allowed_extensions']) ? $req['allowed_extensions'] : null,
                'max_file_size_mb' => $req['max_file_size_mb'],
            ]);
        }

        // Create deadlines (now mandatory)
        foreach ($this->deadlines as $deadline) {
            LicenceDeadline::create([
                'licence_id' => $licence->id,
                'club_id' => $deadline['club_id'],
                'start_at' => $deadline['start_at'],
                'end_at' => $deadline['end_at'],
            ]);
        }

        $this->reset([
            'seasonId', 'leagueId', 'titleRu', 'titleKk', 'titleEn',
            'descriptionRu', 'descriptionKk', 'descriptionEn',
            'startAt', 'endAt', 'isActive', 'requirements', 'deadlines',
            'showCreateModal'
        ]);

        session()->flash('message', 'Лицензия успешно создана');

        // Show toastr notification
        $this->dispatch('showMessage', [
            'type' => 'success',
            'message' => 'Лицензия успешно создана'
        ]);
    }

    public function editLicence($licenceId)
    {
        $licence = Licence::with(['licence_requirements', 'licence_deadlines'])->findOrFail($licenceId);
        $this->authorize('manage-licences');

        $this->editingLicenceId = $licence->id;
        $this->seasonId = $licence->season_id;
        $this->leagueId = $licence->league_id;
        $this->titleRu = $licence->title_ru;
        $this->titleKk = $licence->title_kk;
        $this->titleEn = $licence->title_en;
        $this->descriptionRu = $licence->description_ru;
        $this->descriptionKk = $licence->description_kk;
        $this->descriptionEn = $licence->description_en;
        $this->startAt = $licence->start_at->format('Y-m-d');
        $this->endAt = $licence->end_at->format('Y-m-d');
        $this->isActive = $licence->is_active;

        // Load existing requirements
        $this->requirements = $licence->licence_requirements->map(function($req) {
            return [
                'id' => $req->id,
                'category_id' => $req->category_id,
                'document_id' => $req->document_id,
                'is_required' => $req->is_required,
                'allowed_extensions' => $req->allowed_extensions ?? [],
                'max_file_size_mb' => $req->max_file_size_mb,
            ];
        })->toArray();

        // Load existing deadlines
        $this->deadlines = $licence->licence_deadlines->map(function($deadline) {
            return [
                'id' => $deadline->id,
                'club_id' => $deadline->club_id,
                'start_at' => $deadline->start_at->format('Y-m-d'),
                'end_at' => $deadline->end_at->format('Y-m-d'),
            ];
        })->toArray();

        $this->showEditModal = true;
    }

    public function updateLicence()
    {
        $this->authorize('manage-licences');

        $licence = Licence::findOrFail($this->editingLicenceId);

        // Validate that at least one requirement and deadline are added and have valid data
        $this->validate([
            'requirements' => 'required|array|min:1',
            'requirements.*.category_id' => 'required_without:requirements.*.document_id|integer|exists:category_documents,id',
            'requirements.*.document_id' => 'required_without:requirements.*.category_id|integer|exists:documents,id',
            'deadlines' => 'required|array|min:1',
            'deadlines.*.club_id' => 'required|integer|exists:clubs,id',
            'deadlines.*.start_at' => 'required|date',
            'deadlines.*.end_at' => 'required|date|after:deadlines.*.start_at',
        ], [
            'requirements.required' => 'Необходимо добавить хотя бы одно требование к лицензии',
            'requirements.min' => 'Необходимо добавить хотя бы одно требование к лицензии',
            'requirements.*.category_id.required_without' => 'Для каждого требования необходимо выбрать категорию или документ',
            'requirements.*.document_id.required_without' => 'Для каждого требования необходимо выбрать документ или категорию',
            'deadlines.required' => 'Необходимо добавить хотя бы один дедлайн для клуба',
            'deadlines.min' => 'Необходимо добавить хотя бы один дедлайн для клуба',
            'deadlines.*.club_id.required' => 'Для каждого дедлайна необходимо выбрать клуб',
            'deadlines.*.start_at.required' => 'Для каждого дедлайна необходимо указать дату начала',
            'deadlines.*.end_at.required' => 'Для каждого дедлайна необходимо указать дату окончания',
            'deadlines.*.end_at.after' => 'Дата окончания должна быть позже даты начала',
        ]);

        // Custom validation: Check for duplicate document_id within the same licence
        $documentIds = array_filter(array_column($this->requirements, 'document_id'));
        if (count($documentIds) !== count(array_unique($documentIds))) {
            // Find duplicates for specific error message
            $duplicates = array_diff_assoc($documentIds, array_unique($documentIds));
            $duplicateIds = array_values(array_unique($duplicates));

            // Get document names for better error message
            $documentNames = Document::whereIn('id', $duplicateIds)->pluck('title_ru')->toArray();
            $namesList = implode(', ', $documentNames);

            $this->addError('requirements', "Нельзя добавлять один и тот же документ несколько раз в рамках одной лицензии. Дублирующиеся документы: {$namesList}");

            // Show toastr notification
            $this->dispatch('showMessage', [
                'type' => 'error',
                'message' => "Нельзя добавлять один и тот же документ несколько раз в рамках одной лицензии. Дублирующиеся документы: {$namesList}"
            ]);

            return;
        }

        // Validate remaining fields
        try {
            $this->validate();
        } catch (ValidationException $e) {
            // Errors are already shown via toastr in the overridden validate method
            return;
        }

        $licence->update([
            'season_id' => $this->seasonId,
            'league_id' => $this->leagueId,
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
            'description_ru' => $this->descriptionRu,
            'description_kk' => $this->descriptionKk,
            'description_en' => $this->descriptionEn,
            'start_at' => $this->startAt,
            'end_at' => $this->endAt,
            'is_active' => $this->isActive,
        ]);

        // Update requirements (delete old, create new - now mandatory)
        $licence->licence_requirements()->delete();
        foreach ($this->requirements as $req) {
            LicenceRequirement::create([
                'licence_id' => $licence->id,
                'category_id' => $req['category_id'],
                'document_id' => $req['document_id'],
                'is_required' => $req['is_required'],
                'allowed_extensions' => !empty($req['allowed_extensions']) ? $req['allowed_extensions'] : null,
                'max_file_size_mb' => $req['max_file_size_mb'],
            ]);
        }

        // Update deadlines (delete old, create new - now mandatory)
        $licence->licence_deadlines()->delete();
        foreach ($this->deadlines as $deadline) {
            LicenceDeadline::create([
                'licence_id' => $licence->id,
                'club_id' => $deadline['club_id'],
                'start_at' => $deadline['start_at'],
                'end_at' => $deadline['end_at'],
            ]);
        }

        $this->reset([
            'seasonId', 'leagueId', 'titleRu', 'titleKk', 'titleEn',
            'descriptionRu', 'descriptionKk', 'descriptionEn',
            'startAt', 'endAt', 'isActive', 'requirements', 'deadlines',
            'showEditModal', 'editingLicenceId'
        ]);

        session()->flash('message', 'Лицензия успешно обновлена');

        // Show toastr notification
        $this->dispatch('showMessage', [
            'type' => 'success',
            'message' => 'Лицензия успешно обновлена'
        ]);
    }

    public function deleteLicence($licenceId)
    {
        $this->authorize('delete-licences');

        $licence = Licence::findOrFail($licenceId);

        // Delete related records
        $licence->licence_requirements()->delete();
        $licence->licence_deadlines()->delete();

        $licence->delete();

        session()->flash('message', 'Лицензия удалена');
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->reset([
            'seasonId', 'leagueId', 'titleRu', 'titleKk', 'titleEn',
            'descriptionRu', 'descriptionKk', 'descriptionEn',
            'startAt', 'endAt', 'isActive', 'requirements', 'deadlines'
        ]);
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->reset([
            'seasonId', 'leagueId', 'titleRu', 'titleKk', 'titleEn',
            'descriptionRu', 'descriptionKk', 'descriptionEn',
            'startAt', 'endAt', 'isActive', 'requirements', 'deadlines',
            'editingLicenceId'
        ]);
    }

    public function render()
    {
        return view('livewire.admin.licence-management', [
            'licences' => $this->getLicences(),
        ])->layout(get_user_layout());
    }
}
