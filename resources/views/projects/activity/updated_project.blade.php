@if(count($activity->changes) === 1)
    {{ $activity->user->name }} updated project {{ key($activity->changes) }}
@else
    {{ $activity->user->name }} updated the project
@endif

