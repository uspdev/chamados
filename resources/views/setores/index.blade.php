@extends('laravel-usp-theme::master')

@section('content')
    @include('messages.flash')
    @include('messages.errors')

    <br>
    <div class="mb-2">
        <span class="h3">{{ $data->title }}</span>
        <button class="btn btn-sm btn-success" onclick="toggle_form('add')">
            <i class="fas fa-plus"></i> Novo
        </button>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalScrollable">
            Launch demo modal
        </button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" data-backdrop="static"
        aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h5 class="modal-title" id="exampleModalScrollableTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="form-div mb-4" style="display: none;">
        <div class="card" style="width: 80%">
            <div class="card-body">
                <form method="POST" action="">

                    @foreach ($data->fields as $col)
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2">{{ $col['label'] ?? $col['name'] }}</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="{{ $col['type'] ?? 'text' }}" name="{{ $col['name'] }}"
                                    value="">
                            </div>
                        </div>
                    @endforeach

                    <button class="btn btn-sm btn-warning" onclick="event.preventDefault();cancel_form()">Cancelar</button>
                    <button type="submit" class="btn btn-sm btn-primary">Salvar</button>
                </form>
            </div>
        </div>

    </div>

    @include('common.list-table')

@endsection

@section('javascripts_bottom')
    <script>
        $(document).ready(function() {

            toggle_form = function($action) {
                $('.form-div').slideToggle();
            }

            edit_form = function($id) {
                $('.form-div').slideDown();

            }

            cancel_form = function() {
                $('.form-div').slideUp();

            }

        })

    </script>
@endsection
