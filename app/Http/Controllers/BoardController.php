<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\BoardUser;
use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class BoardController
 *
 * @package App\Http\Controllers
 */
class BoardController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function boards()
    {
        /** @var User $user */
        $user = Auth::user();
        $all_users = User::all();
  
        $boards = Board::with(['user', 'boardUsers']);
  
        if ($user->role === User::ROLE_USER) {
            $boards = $boards->where(function ($query) use ($user) {
                //Suntem in tabele de boards in continuare
                $query->where('user_id', $user->id)
                    ->orWhereHas('boardUsers', function ($query) use ($user) {
                        //Suntem in tabela de board_users
                        $query->where('user_id', $user->id);
                    });
            });
        }
       
        $boards = $boards->paginate(10);

        return view(
            'boards.index',
            [
                'boards' => $boards,
                'all_users' =>$all_users,
            ]
        );
    }

    public function updateBoard(Request $request, $id)
    {
        $board = Board::find($id);
        $error = '';

        if($board) {
            $board->update(['name'=> $request->name]);
        }else{
            $error = 'Board not found!';
        }

        foreach ($request->users as $user) {
            BoardUser::updateOrCreate([
                'board_id' => $id,
                'user_id' => $user         
            ]);
        }
  
        if($request->removedUsers !== null){
            foreach ($request->removedUsers as $user) {
                $user = User::where('name',$user)->first();
                BoardUser::where('board_id',$id)->where('user_id',$user->id)->delete();
            }
        }
        return response()->json(['error' => $error]);
    }

    public function deleteBoard($id)
    {
        $board = Board::find($id);
        $error = '';

        if ($board) {
            $board->delete();

        } else {
            $error = 'Board not found!';
        }

        return response()->json(['error' => $error]);
    }
    
    /**
     * @param $id
     *
     * @return Application|Factory|View|RedirectResponse
     */
    public function board($id)
    {
        /** @var User $user */
        $user = Auth::user();
        $all_users = User::all();
        $boards = Board::query();

        if ($user->role === User::ROLE_USER) {
            $boards = $boards->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhereHas('boardUsers', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    });
            });
        }

        $board = clone $boards;
        $board = $board->where('id', $id)->first();

        $boards = $boards->select('id', 'name')->get();

        if (!$board) {
            return redirect()->route('boards.all');
        }

        $tasks = Task::with('user')->where('board_id',$id)->oldest()->get();
        $status = Task::pluck('status')->unique();

        $list_status=[];
        foreach ($status as $item) {
            if($item == 0){
                array_push($list_status,(object)['id' => $item,'name' => 'created']);
            }else if($item == 1){
                array_push($list_status,(object)['id' => $item,'name' => 'in progress']);
            }else{
                array_push($list_status,(object)['id' => $item,'name' => 'done']);
            }
        }

        return view(
            'boards.view',
            [
                'board' => $board,
                'boards' => $boards,
                'tasks' => $tasks,
                'all_users' => $all_users,
                'list_status' => $list_status
              
            ]
        );
    }
}
