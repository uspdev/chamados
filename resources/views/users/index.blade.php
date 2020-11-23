@extends('master')

@section('content')
    @parent
    <div class="row">
        <div class="col-md-12 form-inline">
            <span class="h4 mt-2">Usuários</span>
            @include('partials.datatable-filter-box')
            @include('users.partials.btn-add-modal')
        </div>
    </div>
    <table class="table table-striped table-sm table-hover datatable-nopagination">
        <thead>
            <tr>
                <th>Número USP</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Ultimo login</th>
                <th>Admin</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->codpes }}</td>
                    <td><a href="users/{{ $user->id }}">{{ $user->name }}</a></td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->telefone }}</td>
                    <td>{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i:s') : ''}}</td>
                    <td>{{ $user->is_admin }}</td>

                    <td style="width: 40px;">
                        <div class="row">
                            <div class="mr-2 form-inline">
                                @include('users.partials.btn-change-user') &nbsp; &nbsp;
                                @include('partials.btn-delete', ['row'=>$user])
                            </div>
                        </div>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>

@endsection

@section('javascripts_bottom')
    @parent
    <script>
        $(document).ready(function() {

            oTable = $('.datatable-nopagination').DataTable({
                dom: 'ti',
                "paging": false
            });

        })

    </script>
@endsection
