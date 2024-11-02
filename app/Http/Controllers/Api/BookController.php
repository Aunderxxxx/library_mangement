<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Book;
use App\Models\Loan;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Requests\BookRequest;
use App\Http\Requests\LoanRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Http\Resources\LoanResource;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function all_books()
    {
        $books = Book::query()->latest('id')->get();
        return ApiResponse::response(200, 'Success', BookResource::collection($books));
    }

    public function new_books(BookRequest $request)
    {
        $data = new Book();
        $data->title = $request->title;
        $data->author = $request->author;
        $data->description = $request->description;
        $data->published_year = $request->published_year;
        $data->save();

        return ApiResponse::response(200, 'Success', $data);
    }

    public function update_books($id, Request $request)
    {
        $data = Book::findOrFail($id);
        $data->title = $request->title ?? $data->title;
        $data->author = $request->author ?? $data->author;
        $data->description = $request->description ?? $data->description;
        $data->published_year = $request->published_year ?? $data->published_year;
        $data->update();

        return ApiResponse::response(200, 'Success', new BookResource($data));
    }

    public function remove_books($id)
    {
        Book::findOrFail($id)->delete();
        return ApiResponse::response(200, 'Deleted success');
    }

    public function borrow_books(LoanRequest $request)
    {
       try{
            DB::beginTransaction();

            $book = Book::where('id', $request->book_id)->where('status', 'available')->first();
            if(!$book){
                return ApiResponse::response(200, 'Book is borrowed');
            }

            $book->status = 'borrowed';
            $book->update();
            $user = Auth::user();

            $loan = new Loan();
            $loan->book_id = $book->id;
            $loan->user_id = $user->id;
            $loan->borrowed_date = date('Y-m-d H:i:s');
            $loan->save();

            DB::commit();

            return ApiResponse::response(200, 'Success', new LoanResource($loan));

        } catch (Exception $e) {
            DB::rollBack();
            return ApiResponse::response(400, $e->getMessage());
        }
    }

    public function borrow_all_books()
    {
        $user_id = Auth::user()->id;
        $loans = Loan::query()->where('user_id', $user_id)->where('status', 0)->get();
        return ApiResponse::response(200, 'Success', LoanResource::collection($loans));
    }

    public function return_books($id)
    {
        try{
            DB::beginTransaction();

            $loan = Loan::query()->where('id', $id)->where('status', 0)->first();
            $loan->return_date = date('Y-m-d H:i:s');
            $loan->status = 1;
            $loan->update();

            $book = $loan->book;
            $book->status = 'available';
            $book->update();

            DB::commit();

            return ApiResponse::response(200, 'Return book success');

        } catch (Exception $e) {
            DB::rollBack();
            return ApiResponse::response(400, $e->getMessage());
        }
    }
}
