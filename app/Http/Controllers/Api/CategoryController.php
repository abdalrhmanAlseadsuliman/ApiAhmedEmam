<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\GeneralTrait;


class CategoryController extends Controller
{
    use GeneralTrait;

    public function index(){
        $categories = Category::select('id', 'name_' . app()->getLocale() .' as name')->get();
        return response()->json($categories);
    }

    public function getCategory(Request $request){
        $category = Category::select('id', 'name_' . app()->getLocale() .' as name')->find($request->id);
        if(!$category){
            return $this->returnError('001','Category not found');
        }
        return $this->returnData('category',$category,'تم جلب البيانات بنجاح');
    }

    public function changeStatus(Request $request){
        $category = Category::where('id', $request->id)->update(['status' => $request->status]);
        return $this->returnSuccessMessage('تم تغيير الحالة بنجاح');
    }

}
