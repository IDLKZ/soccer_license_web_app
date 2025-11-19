<?php

namespace App\Livewire\Admin;

use App\Models\Application;
use App\Models\ApplicationCriterion;
use App\Models\LicenceRequirement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ApplicationDetailedManagement extends Component
{
    public $applicationId;

    public $application;

    public $licence;

    public $club;

    public $user;

    public $activeTab = null;

    public $criteriaTabs = [];

    public $licenceRequirementsByCategory = [];

    // Permissions
    #[Locked]
    public $canView = false;

    public function mount($application_id)
    {
        $this->authorize('view-full-application');

        $user = Auth::user();
        $this->canView = $user->can('view-full-application');

        $this->applicationId = $application_id;
        $this->loadApplication();

        if (! $this->application) {
            abort(404);
        }

        $this->loadTabsAndRequirements();
    }

    private function loadApplication()
    {
        try {
            $this->application = Application::find($this->applicationId);

            if (! $this->application) {
                return;
            }

            $this->licence = $this->application->licence()->with('season', 'league')->first();
            $this->club = $this->application->club;
            $this->user = $this->application->user;
        } catch (\Exception $e) {
            session()->flash('error', 'Ошибка при загрузке заявки: '.$e->getMessage());
        }
    }

    private function loadTabsAndRequirements()
    {
        try {
            // Load all criteria for this application
            $criteria = ApplicationCriterion::with([
                'category_document',
                'application_status',
            ])
                ->where('application_id', $this->applicationId)
                ->get();

            // Group criteria by category for tabs
            $this->criteriaTabs = $criteria->groupBy('category_document.id')->map(function ($groupedCriteria, $categoryId) {
                $category = $groupedCriteria->first()->category_document;

                return [
                    'category_id' => $categoryId,
                    'title_ru' => $category?->title_ru ?? 'Неизвестная категория',
                    'title_kk' => $category?->title_kk ?? 'Белгісіз санат',
                    'title_en' => $category?->title_en ?? 'Unknown category',
                    'criteria' => $groupedCriteria,
                ];
            })->values()->toArray();

            // Set active tab to first category
            if (! empty($this->criteriaTabs)) {
                $this->activeTab = $this->criteriaTabs[0]['category_id'];
            }

            // Load requirements grouped by category
            if ($this->licence) {
                $this->licenceRequirementsByCategory = $this->getLicenceRequirementsByCategory();
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Ошибка при загрузке требований: '.$e->getMessage());
        }
    }

    private function getLicenceRequirementsByCategory()
    {
        try {
            $requirements = LicenceRequirement::with('document')
                ->where('licence_id', $this->licence->id)
                ->get();

            $grouped = [];

            foreach ($requirements as $requirement) {
                $documentId = $requirement->document_id;

                if (! isset($grouped[$documentId])) {
                    $grouped[$documentId] = [
                        'document' => $requirement->document,
                        'requirements' => [],
                    ];
                }

                $grouped[$documentId]['requirements'][] = [
                    'id' => $requirement->id,
                    'description_ru' => $requirement->description_ru,
                    'description_kk' => $requirement->description_kk,
                    'description_en' => $requirement->description_en,
                    'is_required' => $requirement->is_required,
                ];
            }

            return $grouped;
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getUploadedDocumentsForRequirement($documentId)
    {
        try {
            if (! $this->application) {
                return collect();
            }

            // Get current criterion based on active tab
            $criterion = $this->getCurrentCriterion();

            if (! $criterion) {
                return collect();
            }

            // Get documents uploaded for this requirement
            $documents = \DB::table('application_documents')
                ->where('application_id', $this->application->id)
                ->where('document_id', $documentId)
                ->where('category_id', $criterion->category_id)
                ->orderBy('created_at', 'desc')
                ->get();

            return $documents;
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function getCurrentCriterion()
    {
        try {
            if (! $this->activeTab) {
                return null;
            }

            return ApplicationCriterion::where('application_id', $this->applicationId)
                ->where('category_id', $this->activeTab)
                ->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function downloadDocument($fileUrl)
    {
        try {
            if (Storage::disk('public')->exists($fileUrl)) {
                return Storage::disk('public')->download($fileUrl);
            }

            session()->flash('error', 'Файл не найден.');
        } catch (\Exception $e) {
            session()->flash('error', 'Ошибка при скачивании файла: '.$e->getMessage());
        }
    }

    public function setActiveTab($categoryId)
    {
        $this->activeTab = $categoryId;
    }

    public function getApplicationStatusColor($statusValue)
    {
        $colors = [
            'draft' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
            'awaiting-first-check' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            'first-check-revision' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            'awaiting-industry-check' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
            'industry-check-revision' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
            'awaiting-control-check' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300',
            'control-check-revision' => 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-300',
            'awaiting-final-decision' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-300',
            'approved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            'partially-approved' => 'bg-lime-100 text-lime-800 dark:bg-lime-900 dark:text-lime-300',
            'revoked' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
        ];

        return $colors[$statusValue] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
    }

    public function getCriterionStatusColor($statusValue)
    {
        $colors = [
            'draft' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
            'awaiting-first-check' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            'first-check-revision' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            'awaiting-industry-check' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
            'industry-check-revision' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
            'awaiting-control-check' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300',
            'control-check-revision' => 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-300',
            'awaiting-final-decision' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-300',
            'fully-approved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            'partially-approved' => 'bg-lime-100 text-lime-800 dark:bg-lime-900 dark:text-lime-300',
            'revoked' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
        ];

        return $colors[$statusValue] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
    }

    public function render()
    {
        return view('livewire.admin.application-detailed-management')->layout(get_user_layout());
    }
}
