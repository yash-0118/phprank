<x-admin-layout>
    @foreach($settings as $setting)
    {{$setting->group}} -> {{$setting->name}} -> {{$setting->payload}} <br>
    @endforeach
</x-admin-layout>