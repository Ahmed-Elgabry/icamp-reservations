<?php

namespace App\Exports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TasksExport implements FromCollection, WithHeadings, WithMapping
{
    protected $status;
    protected $employeeId;
    protected $dateFrom;
    protected $dateTo;

    public function __construct($status, $employeeId, $dateFrom, $dateTo)
    {
        $this->status = $status;
        $this->employeeId = $employeeId;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function collection()
    {
        $query = Task::with(['assignedUser', 'creator']);

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->employeeId) {
            $query->where('assigned_to', $this->employeeId);
        }

        if ($this->dateFrom) {
            $query->whereDate('due_date', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('due_date', '<=', $this->dateTo);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Title',
            'Description',
            'Assigned To',
            'Created By',
            'Due Date',
            'Status',
            'Priority',
            'Completion Date',
            'Failure Reason'
        ];
    }

    public function map($row): array
    {
        return [
            $row->title,
            $row->description,
            $row->assignedUser->name,
            $row->creator->name,
            $row->due_date->format('Y-m-d'),
            ucfirst(str_replace('_', ' ', $row->status)),
            ucfirst($row->priority),
            $row->status == 'completed' ? $row->updated_at->format('Y-m-d') : 'N/A',
            $row->failure_reason ?? 'N/A'
        ];
    }
}
