@if(old('product') || old('package') || old('promo') || old('discount'))
    <script>$('#compute').val(0)</script>
    @if(old('product'))
    @foreach(old('product') as $key=>$product)
        <script>oldProduct({{$product}},{{old("productQty.".$key)}})</script>
    @endforeach
    @endif
    @if(old('package'))
    @foreach(old('package') as $key=>$package)
        <script>oldPackage({{$package}},{{old("packageQty.".$key)}})</script>
    @endforeach
    @endif
    @if(old('promo'))
    @foreach(old('promo') as $key=>$promo)
        <script>oldPromo({{$promo}},{{old("promoQty.".$key)}})</script>
    @endforeach
    @endif
    @if(old('discount'))
    @foreach(old('discount') as $key=>$discount)
        <script>oldDiscount({{$discount}})</script>
    @endforeach
    @endif
@endif