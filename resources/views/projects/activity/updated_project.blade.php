@if(count($activity->changes) === 1)
    {{ auth()->user()->name }} updated project {{ key($activity->changes) }}
@else
    {{ auth()->user()->name }} updated the project
@endif

