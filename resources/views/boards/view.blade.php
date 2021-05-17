@extends('layout.main')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Board view</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{route('boards.all')}}">Boards</a></li>
                        <li class="breadcrumb-item active">Board</li>
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
                <h3 class="card-title">{{$board->name}}</h3>
            </div>
           
            <div class="card-body">
                <select class="custom-select rounded-0" id="changeBoard">
                    @foreach($boards as $selectBoard)
                        <option @if ($selectBoard->id === $board->id) selected="selected" @endif value="{{$selectBoard->id}}">{{$selectBoard->name}}</option>
                    @endforeach
                </select>
            </div>
       
        <!-- /.card -->
   
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Assignment</th>
                            <th>Status</th>
                            <th>Date of creation</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tasks as $task)
                            <tr>
                                <td>{{$task->id}}</td>
                                <td data-toggle="modal" data-task="{{json_encode($task)}}" class="taskName" style="cursor:pointer">{{$task->name}}</td>  
                                <td data-toggle="modal" data-task="{{json_encode($task)}}" class="taskDescription" style="cursor:pointer">{{$task->description}}</td>  

                                @php
                                    $userAssigned=[];    
                                    $null="No";
                                @endphp

                                @if ($task->assignment !== NULL)
                                    @php    
                                        array_push($userAssigned,$task->user->id);
                                    @endphp
                                @endif

                                <td data-toggle="modal" data-task="{{json_encode($task)}}" data-user="{{json_encode($userAssigned)}}" class="taskAssignment" style="cursor:pointer">{{$task->assignment == NULL ? "____" : $task->user->name}}</td>  
                                <td data-toggle="modal" data-task="{{json_encode($task)}}" class="taskStatus" style="cursor:pointer">{{($task->status == 0) ? "created" : (($task->status == 1) ? "in progress" : "done" )}}</td>
                                <td data-toggle="modal" data-task="{{json_encode($task)}}" class="taskDateCreation" style="cursor:pointer">{{$task->created_at}}</td>  
                                <td>
                                    <div class="btn-group">
                                        @if (auth()->user()->name == $task->board->user->name && auth()->user()->role == 0)
                                            <button class="btn btn-xs btn-danger" type="button" data-task="{{json_encode($task)}}" data-toggle="modal" data-target="#taskDeleteModal"><i class="fas fa-trash"></i></button>
                                        @elseif (auth()->user()->role == 1)
                                            <button class="btn btn-xs btn-danger" type="button" data-task="{{json_encode($task)}}" data-toggle="modal" data-target="#taskDeleteModal"><i class="fas fa-trash"></i></button>
                                        @else 
                                            <button class="btn btn-xs btn-danger" type="button" data-bs-toggle="tooltip" data-bs-placement="left" title="You don’t currently have permission to access this section"><i class="far fa-times-circle"></i></button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="taskAssignmentModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Change Assignment</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger hidden" id="taskEditAlert"></div>
                        <input type="hidden" name="boardEditId" id="boardEditId" />
                        <input type="hidden" name="taskEditId" id="taskEditId" />
                        <div class="form-group">
                            <label for="userAssigned">Select user assigned to this board</label>
                            <select class="select2" name="userAssigned" id="userAssigned" data-placeholder="Select a User" style="width: 40%;">
                                @foreach ($all_users as $user)
                                    <option value={{$user->id}}>{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="taskAssignmentButton">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

            <div class="modal fade" id="taskNameModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Change Name</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger hidden" id="taskEditAlert"></div>
                            <input type="text" name="taskEditName" id="taskEditName">
                            <input type="hidden" name="taskEditId" id="taskEditId" />     
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="taskNameButton">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="taskDescriptionModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Change Description</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger hidden" id="taskEditAlert"></div>
                            <textarea class="form-control" name="taskEditDescription" id="taskEditDescription"rows="3"></textarea>
                            <input type="hidden" name="taskEditId" id="taskEditId" />
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="taskDescriptionButton">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="taskStatusModel">
                <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Change Status</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-danger hidden" id="taskEditAlert"></div>
                                <input type="hidden" name="taskEditId" id="taskEditId" />
                                    <div class="form-group">
                                        <label for="status">Select a status</label>
                                            <select class="select2" name="status" id="status" data-placeholder="Select a Status" style="width: 30%;">
                                                @foreach ($list_status as $status)        
                                                    <option value={{$status->id}}>{{$status->name}}</option>
                                                @endforeach
                                            </select>
                                    </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="taskStatusButton">Save changes</button>
                            </div>
                        </div>
                </div>
            </div>

            <div class="modal fade" id="taskDateCreationModel">
                <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Change date of creation</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-danger hidden" id="taskEditAlert"></div>
                                <input type="hidden" name="taskEditId" id="taskEditId" />
                                <input type="hidden" name="selectedDateTime" id="selectedDateTime" />
                                    <div class="form-group">
                                        <input id="datetimepicker" type="text" >
                                    </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="taskDateCreationButton">Save changes</button>
                            </div>
                        </div>
                </div>
            </div>
   
            <div class="modal fade" id="taskDeleteModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Delete task</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger hidden" id="taskDeleteAlert"></div>
                            <input type="hidden" name="taskDeleteId" id="taskDeleteId" value="" />
                            <p>Are you sure you want to delete this task: <span id="taskDeleteName"></span>?</p>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                            <button type="button" class="btn btn-primary" id="taskDeleteButton">Yes</button>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- /.content -->
@endsection