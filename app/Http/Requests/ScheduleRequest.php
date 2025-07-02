<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Ramsey\Uuid\Type\Integer;

class ScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'category_id' => ['nullable', 'integer', 'exists:schedule_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'time_type' => ['required', 'in:normal,all_day,undecided'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'start_time' => ['exclude_unless:time_type,normal', 'required', 'date_format:H:i'],
            'end_time' => ['exclude_unless:time_type,normal', 'required', 'date_format:H:i'],
            'participants' => ['required', 'array'],
            'participants.*' => ['integer', 'exists:users,id'],
            'memo' => ['nullable', 'string', 'max:65535'],
            'private_flg' => ['required', 'boolean']
        ];

        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function($validator) {
            $start = $this->input('start_date') . ' ' . $this->input('start_time');
            $end = $this->input('end_date') . ' ' . $this->input('end_time');

            if ($this->input('time_type') === 'normal') {
                try {
                    $startDt = Carbon::createFromFormat('Y-m-d H:i', $start);
                    $endDt = Carbon::createFromFormat('Y-m-d H:i', $end);

                    if ($endDt->lessThanOrEqualTo($startDt)) {
                        $validator->errors()->add('end_date', '終了日時は開始日時より後にしてください。');
                    }
                } catch (\Exception $e) {
                    $validator->errors()->add('start_date', '開始日時の形式が不正です。');
                    $validator->errors()->add('end_date', '終了日時の形式が不正です。');
                }
            }
        });
    }
}
