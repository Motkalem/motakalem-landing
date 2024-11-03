@extends('admin.layouts.master')

@push('styles')
    <link href="{{ asset('admin/table.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div class="gap-20 row pos-r" style="position: relative; height: 1095px;">
        <div class="col-md-12">
            <div class="mx-4 text-end">
            </div>

            <div class="p-20 mt-4 bgc-white bd">
                <table class="table table-striped table-class">
                    <thead>
                    <tr>
                        <th>اسم المتعاقد</th>
                        <th>رقم العقد</th>
                        <th>تاريخ البدء</th>
                        <th class="text-center"> الهاتف</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($course->contracts??[] as $contractor) <tr>
                        <td>{{ $contractor->name }}</td>
                        <td>{{ $contractor->id }}</td>
                        <td>{{ $contractor->created_at }}</td>
                        <td style="direction: ltr" class="text-center">{{ $contractor->phone }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
