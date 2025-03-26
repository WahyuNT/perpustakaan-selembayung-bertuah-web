<?php

namespace App\Livewire;

use App\Models\Book;
use App\Models\LoanTransaction;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class AdminLoanTransactionComp extends Component
{
    use WithPagination;
    public $user_id, $book_id, $status, $borrowed_at, $returned_at, $due_date, $condition, $fine, $point;
    public $search;
    public $confirmDelete;
    public $editId;
    public $users;
    public $books;
    public $finePoint;

    public function mount()
    {


        $this->users = User::all();
        $this->books = Book::all();
    }

    public function render()
    {
        $data = LoanTransaction::when($this->search, function ($query) {
            $query->where('loan_id', 'like', '%' . $this->search . '%');
        })->whereHas('user', function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%');
        })
            ->whereHas('book', function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->orderby('created_at', 'desc')
            ->paginate('10');



        $this->countFinePoint();
        return view('livewire.admin-loan-transaction-comp', compact('data'))->extends('layouts.master-admin');
    }
    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function resetInput()
    {

        $this->editId = '';
        $this->user_id = '';
        $this->book_id = '';
        $this->status = '';
        $this->borrowed_at = '';
        $this->returned_at = '';
        $this->due_date = '';
        $this->condition = '';
        $this->fine = '';
        $this->point = '';
    }
    public function edit($id)
    {
        $this->editId = $id;
        $data = LoanTransaction::find($id);
        $this->user_id = $data->user_id;
        $this->book_id = $data->book_id;
        $this->status = $data->status;
        $this->borrowed_at = $data->borrowed_at;
        $this->returned_at = $data->returned_at;
        $this->due_date = $data->due_date;
        $this->condition = $data->condition;
    }
    public function countFinePoint()
    {
        if ($this->status === 'returned') {
            $returnedAt = Carbon::parse($this->returned_at);
            $dueDate = Carbon::parse($this->due_date);
            if ($this->condition === 'hilang') {
                $this->fine = 25; // Hilang → -25
                $this->point = 0;
            } elseif ($returnedAt <= $dueDate) {
                $this->fine = 0;
                $this->point = 10; // Tepat waktu → +10
            } elseif ($returnedAt->diffInDays($dueDate) <= 3) {
                $this->fine = 15; // Terlambat 1-3 hari → -15
                $this->point = 0;
            } elseif ($returnedAt->diffInDays($dueDate) > 3) {
                $this->fine = 25; // Terlambat >3 hari → -25
                $this->point = 0;
            }
            $this->finePoint = max($this->fine, $this->point);
            if ($this->finePoint === 10) {
                $this->finePoint = '+' . $this->finePoint;
            } else {
                $this->finePoint = '-' . $this->finePoint;
            }
        }
    }
    public function storeEdit()
    {
        $this->validate([
            'user_id' => 'required',
            'book_id' => 'required',
            'status' => 'required',
            'borrowed_at' => 'required',
            'returned_at' => 'required',
            'due_date' => 'required',
            'condition' => 'required',
        ]);

        $id = $this->editId;
        $data = LoanTransaction::find($id);
        $data->user_id = $this->user_id;
        $data->book_id = $this->book_id;
        $data->status = $this->status;
        $data->borrowed_at = $this->borrowed_at;
        $data->returned_at = $this->returned_at;
        $data->due_date = $this->due_date;
        $data->condition = $this->condition;
        $data->fine = preg_replace('/[^0-9]/', '', $this->fine);
        $data->point = preg_replace('/[^0-9]/', '', $this->point);



        if ($data->save()) {
            LivewireAlert::title('Data Berhasil Diubah!')
                ->position('top-end')
                ->toast()
                ->success()
                ->show();

            $this->dispatch('close-modal');
            $this->resetInput();
        } else {
            LivewireAlert::title('Data Gagal Diubah!')
                ->position('top-end')
                ->toast()
                ->error()
                ->show();
        }
    }
    public function delete($id)
    {
        $data = LoanTransaction::where('id', $id)->first();
        if ($data->delete()) {
            LivewireAlert::title('Data berhasil dihapus!')
                ->position('top-end')
                ->toast()
                ->success()
                ->show();
        } else {
            LivewireAlert::title('Data gagal dihapus!')
                ->position('top-end')
                ->toast()
                ->error()
                ->show();
        }
    }
}
