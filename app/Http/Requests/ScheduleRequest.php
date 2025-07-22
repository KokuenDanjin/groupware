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
            'participants' => ['required', 'array'],
            'participants.*' => ['integer', 'exists:users,id'],
            'memo' => ['nullable', 'string', 'max:65535'],
            'private_flg' => ['required', 'boolean']
        ];

        return $rules;
    }

    public function withValidator($validator)
    {
        // start/end_time用のルール
        $validator->sometimes(
            ['start_time', 'end_time'],
            ['required', 'date_format:H:i'],
            function($input) {
                return $input->time_type === 'normal';
            }
        );

        // 終了日時が開始日時より後かチェック
        $validator->after(function($validator) {
            $timeType = $this->input('time_type');

            // normalなら日時、それ以外なら日付のみで判定
            if ($timeType === 'normal') {
                $startDate = $this->input('start_date');
                $endDate = $this->input('end_date');
                $startTime = $this->input('start_time');
                $endTime = $this->input('end_time');

                try {
                    $start = Carbon::createFromFormat('Y-m-d H:i', "$startDate $startTime");
                    $end = Carbon::createFromFormat('Y-m-d H:i', "$endDate $endTime");

                    if ($end->lessThanOrEqualTo($start)) {
                        if ($startDate === $endDate) { // 時間だけがNGの場合
                            $validator->errors()->add('end_time', '終了時間は開始時間より後にしてください。');
                        } else { // 日付がそもそもNGの場合
                            $validator->errors()->add('end_date', '終了日は開始日と同じか後にしてください。');
                        }
                    }
                } catch (\Exception $e) {
                    $validator->errors()->add('start_date', '開始日時の形式が不正です。');
                    $validator->errors()->add('end_date', '終了日時の形式が不正です。');
                }
            } else {
                $startDate = $this->input('start_date');
                $endDate = $this->input('end_date');

                try {
                    $startDt = Carbon::createFromFormat('Y-m-d', $startDate);
                    $endDt = Carbon::createFromFormat('Y-m-d', $endDate);

                    if ($endDt->lessThan($startDt)) {
                        $validator->errors()->add('end_date', '終了日は開始日と同じか後にしてください。');
                    }
                } catch (\Exception $e) {
                    $validator->errors()->add('start_date', '開始日時の形式が不正です。');
                    $validator->errors()->add('end_date', '終了日時の形式が不正です。');
                }
            }
        });
    }
}