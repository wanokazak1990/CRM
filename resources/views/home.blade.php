@extends('layout')

@section('head')
    @parent
    {!! Charts::assets() !!}
@endsection





@section('right')
<div class="row">
    <div class="col-sm-4"> 
    {!! $chartBrand->render() !!}
    </div>
    
    <div class="clearfix"></div>

    @foreach ($chartModel as $chartM)
        <div class="col-sm-4">
           {!! $chartM->render() !!} 
        </div>
    @endforeach
</div>
<style>
    .raphael-group-34-creditgroup{
        display: none;
    }
</style>
@endsection






@section('scripts')
    @parent

@endsection