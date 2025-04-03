@extends('admin.layouts.master')

@push('styles')
    <link href="{{ asset('admin/table.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div class="p-20 bgc-white bd">
        <h6 class="c-grey-900">استفسارات البرامج</h6>
        <div class="mx-4 text-end">
            <div class="mT-30">
                <form action="{{URL::current()}}">
                    <div class="pb-4 w-25 d-flex align-items-center">
                        <input type="search" name="search" value="{{data_get($_GET,'search')}}" class="form-control" id="search" placeholder="بحث"/>
                        <button class="btn btn-primary btn-sm mx-2">بحث</button>
                    </div>
                </form>
                <table class="table table-striped table-class">
                    <thead>
                    <tr>
                        <th class="text-center">الإسم</th>
                        <th class="text-center">الجوال</th>
                        <th class="text-center">العمر</th>
                        <th class="text-center">الرسالة</th>
                        <th class="text-center">الإجراءات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($programInquiries as $inquiry)
                        <tr>
                            <td>{{ $inquiry->name }}</td>
                            <td>{{ $inquiry->mobile_number }}</td>
                            <td>{{ $inquiry->age }}</td>
                            <td title="{{$inquiry->message}}">{{ \Illuminate\Support\Str::limit($inquiry->message , 50) }}</td>
                            <td class="text-center">

                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#showMessageModal" data-message="{{ $inquiry->message }}">
                                    عرض
                                </button>

                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="{{ $inquiry->id }}">
                                    حذف
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="pagination">
                    {{ $programInquiries->links() }}
                </div>

                <div class="modal fade" id="showMessageModal" tabindex="-1" aria-labelledby="showMessageModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="showMessageModalLabel">الرسالة</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="messageContent">
                                <!-- Message content will be dynamically inserted here -->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">تأكيد الحذف</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        هل أنت متأكد أنك تريد حذف هذه الرسالة ؟
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <form id="deleteForm" method="POST" action="">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">حذف</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var deleteModal = document.getElementById('deleteModal');
                    deleteModal.addEventListener('show.bs.modal', function (event) {
                        var button = event.relatedTarget;
                        var inquiryId = button.getAttribute('data-id');
                        var form = document.getElementById('deleteForm');
                        form.action = "{{ route('dashboard.program-inquires.destroy', ':id') }}".replace(':id', inquiryId);
                    });
                });

                // Add event listener to pass message content to the modal
                var showMessageModal = document.getElementById('showMessageModal');
                showMessageModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget; // Button that triggered the modal
                    var message = button.getAttribute('data-message'); // Extract info from data-* attributes

                    // Update the modal's content
                    var modalBody = showMessageModal.querySelector('.modal-body');
                    modalBody.textContent = message;
                });
            </script>
    @endpush
@endsection
