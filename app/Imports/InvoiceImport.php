<?php

namespace App\Imports;

use App\Constants\Currency;
use App\Constants\DocumentTypes;
use App\Constants\ImportStatus;
use App\Constants\InvoiceStatus;
use App\Models\Import;
use App\Models\Invoice;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\ImportFailed;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Validators\ValidationException;

class InvoiceImport implements ShouldQueue, ToModel, WithBatchInserts, WithChunkReading, WithEvents, WithHeadingRow, WithValidation
{
    public function __construct(
        protected Import $import
    ) {}

    public function model(array $row): Invoice
    {
        return new Invoice([
            'reference' => $row['reference'],
            'status' => InvoiceStatus::PENDING->name,
            'document_number' => $row['document_number'],
            'document_type' => $row['document_type'],
            'name' => $row['name'],
            'surname' => $row['surname'],
            'email' => $row['email'],
            'mobile' => $row['mobile'],
            'description' => $row['description'],
            'currency' => $row['currency'],
            'amount' => $row['amount'],
            'expiration_date' => $row['expiration_date'],
            'microsite_id' => $this->import->microsite_id,
        ]);
    }

    /**
     * Batch to insert to database. Only for ToModel.
     */
    public function batchSize(): int
    {
        return 100;
    }

    /**
     * Chunk to read from file
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    public function rules(): array
    {
        $microsite_id = $this->import->microsite_id;

        return [
            'reference' => [
                'required', 'string', 'max:40',
                'unique:invoices,reference,NULL,id,microsite_id,'.$microsite_id,
            ],
            'amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', Rule::in(Currency::toArray())],
            'name' => ['required', 'string', 'max:100'],
            'surname' => ['required', 'string', 'max:100'],
            'document_number' => ['required', 'alpha_num', 'max:40'],
            'document_type' => ['required', Rule::in(DocumentTypes::toArray())],
            'email' => ['required', 'email', 'max:100'],
            'mobile' => ['nullable', 'max:20'],
            'description' => ['nullable', 'string', 'max:512'],
            'expiration_date' => ['required', 'date'],
            'created_at' => ['nullable', 'date'],
        ];
    }

    public function registerEvents(): array
    {
        return [
            ImportFailed::class => function (ImportFailed $event) {
                $exception = $event->getException();

                if ($exception instanceof ValidationException) {
                    $this->import->errors = array_map(
                        fn (Failure $failure) => $failure->toArray()[0],
                        $exception->failures()
                    );
                } else {
                    $this->import->errors = Arr::wrap($event->getException()->getMessage());
                }

                $this->import->status = ImportStatus::FAILED;
                $this->import->save();

                Storage::disk(Import::DISK)->delete($this->import->path);

                Invoice::whereBelongsTo($this->import)->delete();
            },
            AfterImport::class => function () {
                $this->import->status = ImportStatus::COMPLETED;
                $this->import->save();

                Storage::disk(Import::DISK)->delete($this->import->path);
            },
        ];
    }
}
