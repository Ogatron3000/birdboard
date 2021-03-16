<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateProjectRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('update', $this->project());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'       => 'sometimes|required',
            'description' => 'sometimes|required',
            'notes'       => 'nullable',
        ];
    }

    public function project()
    {
        // if we use route model binding in controller
        return $this->route('project');

        // else
        // return Project::findOrFail($this->route('project'));
    }

    public function save()
    {
        // tap (higher-order tap) returns the original target (project) after calling method on it (update)
        return tap($this->project())->update($this->validated());
    }
}
