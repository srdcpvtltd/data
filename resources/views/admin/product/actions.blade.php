<div class="btn-group" role="group">
    <a href="{{route('ch-admin.product.edit', [$id])}}" class="btn text-info bgc-white bdrs-2 mR-3 cur-p">
        <i class="ti-pencil"></i>
    </a>
    <button type="button" data-url="{{route('ch-admin.product.destroy', [$id])}}" onclick="deleteEntry(event, this)" class="btn text-danger bgc-white bdrs-2 mR-3 cur-p">
        <i class="ti-trash"></i>
    </button>
</div>
