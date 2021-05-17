@extends('layout.main')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Boards</h1>          
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Boards</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Boards list</h3>
            </div>

            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Name</th>
                            <th>User</th>
                            <th>Members</th>
                            <th style="width: 40px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($boards as $board)
                            <tr>
                                <td>{{$board->id}}</td>
                                <td>
                                    <a href="{{route('board.view', ['id' => $board->id])}}" class="link">{{$board->name}}</a>
                                </td>
                                <td>{{$board->user->name}}</td>
                                <td>
                                    {{count($board->boardUsers)}}
                                </td>
                                
                                @php
                                    $usersAssigned=[];
                                @endphp

                                @foreach ($board->boardUsers as $boardUser)
                                    @php    
                                        array_push($usersAssigned,$boardUser->user_id);
                                    @endphp
                                 
                                @endforeach
                             
                                <td>
                                    <div class="btn-group">
                                        @if (auth()->user()->name == $board->user->name && auth()->user()->role == 0)
                                            <button class="btn btn-xs btn-primary" type="button" data-board="{{json_encode($board)}}" data-users="{{json_encode($usersAssigned)}}" data-toggle="modal" data-target="#boardEditModal"><i class="fas fa-edit"></i></button>
                                            <button class="btn btn-xs btn-danger" type="button" data-board="{{json_encode($board)}}" data-toggle="modal" data-target="#boardDeleteModal"><i class="fas fa-trash"></i></button>
                                        @elseif (auth()->user()->role == 1)
                                            <button class="btn btn-xs btn-primary" type="button" data-board="{{json_encode($board)}}" data-users="{{json_encode($usersAssigned)}}" data-toggle="modal" data-target="#boardEditModal"><i class="fas fa-edit"></i></button>
                                            <button class="btn btn-xs btn-danger" type="button" data-board="{{json_encode($board)}}" data-toggle="modal" data-target="#boardDeleteModal"><i class="fas fa-trash"></i></button>
                                        @else 
                                            <button class="btn btn-xs btn-primary" type="button" data-bs-toggle="tooltip" data-bs-placement="left" title="You don’t currently have permission to access this section"><i class="far fa-times-circle"></i></button>
                                            <button class="btn btn-xs btn-danger" type="button" data-bs-toggle="tooltip" data-bs-placement="left" title="You don’t currently have permission to access this section"><i class="far fa-times-circle"></i></button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- /.card-body -->
            <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                    @if ($boards->currentPage() > 1)
                        <li class="page-item"><a class="page-link" href="{{$boards->previousPageUrl()}}">&laquo;</a></li>
                        <li class="page-item"><a class="page-link" href="{{$boards->url(1)}}">1</a></li>
                    @endif

                    @if ($boards->currentPage() > 3)
                        <li class="page-item"><span class="page-link page-active">...</span></li>
                    @endif
                    @if ($boards->currentPage() >= 3)
                        <li class="page-item"><a class="page-link" href="{{$boards->url($boards->currentPage() - 1)}}">{{$boards->currentPage() - 1}}</a></li>
                    @endif

                    <li class="page-item"><span class="page-link page-active">{{$boards->currentPage()}}</span></li>

                    @if ($boards->currentPage() <= $boards->lastPage() - 2)
                        <li class="page-item"><a class="page-link" href="{{$boards->url($boards->currentPage() + 1)}}">{{$boards->currentPage() + 1}}</a></li>
                    @endif

                    @if ($boards->currentPage() < $boards->lastPage() - 2)
                        <li class="page-item"><span class="page-link page-active">...</span></li>
                    @endif

                    @if ($boards->currentPage() < $boards->lastPage() )
                        <li class="page-item"><a class="page-link" href="{{$boards->url($boards->lastPage())}}">{{$boards->lastPage()}}</a></li>
                        <li class="page-item"><a class="page-link" href="{{$boards->nextPageUrl()}}">&raquo;</a></li>
                    @endif
                </ul>
            </div>
        </div>
        <!-- /.card -->

        <div class="modal fade" id="boardEditModal">
            <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Edit board</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger hidden" id="boardEditAlert"></div>
                            
                            <input type="text" name="boardEditName" id="boardEditName">
                            <input type="hidden" name="boardEditId" id="boardEditId" />
                                <div class="form-group">
                                    <label for="usersAssigned">Select user assigned to board</label>
                                        <select class="select2" name="usersAssigned[]" id="usersAssigned" multiple="multiple" data-placeholder="Select a User" style="width: 100%;">
                                            @foreach ($all_users as $user)        
                                                <option value={{$user->id}}>{{$user->name}}</option>
                                            @endforeach
                                        </select>
                                </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="boardEditButton">Save changes</button>
                        </div>
                    </div>
            </div>
        </div>

        <div class="modal fade" id="boardDeleteModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Delete board</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger hidden" id="boardDeleteAlert"></div>
                        <input type="hidden" name="boardDeleteId" id="boardDeleteId" value="" />
                        <p>Are you sure you want to delete this board: <span id="boardDeleteName"></span>?</p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-primary" id="boardDeleteButton">Yes</button>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!-- /.content -->
@endsection