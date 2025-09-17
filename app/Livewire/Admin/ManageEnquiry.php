<?php

namespace App\Livewire\Admin;

use App\Models\Enquiry;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;

class ManageEnquiry extends Component
{
    use WithPagination;

    public $activeTab = 'unread'; // unread, read, all
    public $selectedEnquiry = null;
    public $showModal = false;
    public $search = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = [
        'activeTab' => ['except' => 'unread'],
        'search' => ['except' => ''],
    ];

    public function mount()
    {
        $this->activeTab = 'unread';
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function markAsRead($enquiryId)
    {
        $enquiry = Enquiry::findOrFail($enquiryId);
        $enquiry->markAsRead();
        
        session()->flash('message', 'Enquiry marked as read.');
    }

    public function markAsUnread($enquiryId)
    {
        $enquiry = Enquiry::findOrFail($enquiryId);
        $enquiry->markAsUnread();
        
        session()->flash('message', 'Enquiry marked as unread.');
    }

    public function viewEnquiry($enquiryId)
    {
        $this->selectedEnquiry = Enquiry::findOrFail($enquiryId);
        $this->showModal = true;
        
        // Mark as read when viewed
        if (!$this->selectedEnquiry->is_read) {
            $this->selectedEnquiry->markAsRead();
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedEnquiry = null;
    }

    public function deleteEnquiry($enquiryId)
    {
        Enquiry::findOrFail($enquiryId)->delete();
        session()->flash('message', 'Enquiry deleted successfully.');
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    #[Computed]
    public function enquiries()
    {
        $query = Enquiry::query();

        // Apply tab filter
        if ($this->activeTab === 'read') {
            $query->read();
        } elseif ($this->activeTab === 'unread') {
            $query->unread();
        }

        // Apply search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('subject', 'like', '%' . $this->search . '%')
                  ->orWhere('message', 'like', '%' . $this->search . '%');
            });
        }

        // Apply sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        return $query->paginate(10);
    }

    #[Computed]
    public function unreadCount()
    {
        return Enquiry::unread()->count();
    }

    #[Computed]
    public function readCount()
    {
        return Enquiry::read()->count();
    }

    #[Computed]
    public function totalCount()
    {
        return Enquiry::count();
    }

    public function render()
    {
        return view('livewire.admin.manage-enquiry')
            ->layout('components.layouts.admin')
            ->title('Manage Enquiries - Admin');
    }
}