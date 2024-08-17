@extends('admin.layouts.master')

@push('styles')
    <link href="{{ asset('admin/table.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="gap-20 row pos-r" style="position: relative; height: 1095px;">
    <div class="col-md-12">
        <div class="mx-4 text-end">
            <a href="{{ route('dashboard.installment-payments.create') }}" class="px-4 btn btn-primary">
                + إنشاء
            </a>
        </div>

        <div class="p-20 mt-4 bgc-white bd">

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
                هل أنت متأكد أنك تريد حذف هذه الدفعة؟
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
    function copyToClipboard(url) {
        navigator.clipboard.writeText(url).then(function() {
            alert('Link copied to clipboard');
        }, function(err) {
            alert('Failed to copy: ', err);
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        var deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var paymentId = button.getAttribute('data-id');
            var form = document.getElementById('deleteForm');
            form.action = "{{ route('dashboard.payments.destroy', ':id') }}".replace(':id', paymentId);
        });
    });
</script>
@endpush
@endsection
