@extends('admin.layouts.master')

@push('styles')
    <link href="{{ asset('admin/table.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="p-20 bgc-white bd">
    <h6 class="c-grey-900">رسائل إتصل بنا  </h6>
    <div class="mx-4 text-end">
    <div class="mT-30">
        <table class="table table-striped table-class">
            <thead>
                <tr>
                    <th class="text-center">الإسم</th>
                    <th class="text-center">الجوال</th>
                    <th class="text-center">البريد الإلكتروني</th>
                    <th class="text-center">الرسالة</th>
                    <th class="text-center">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contactMessages as $student)
                <tr>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->phone }}</td>
                    <td>{{ $student->email }}</td>
                    <td title="{{$student->message}}">{{ \Illuminate\Support\Str::limit($student->message , 50) }}</td>
                    <td class="text-center">

                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#showMessageModal" data-message="{{ $student->message }}">
                            عرض
                        </button>

                         <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="{{ $student->id }}">
                            حذف
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination">
            {{ $contactMessages->links() }}
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
            var studentId = button.getAttribute('data-id');
            var form = document.getElementById('deleteForm');
            form.action = "{{ route('dashboard.contact-messages.destroy', ':id') }}".replace(':id', studentId);
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
