<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Category\CategoryCollection;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Category::whereNull('parent')->with('children')->get();
        return view('admin.category.list', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request )
    {
        return view('admin.category.create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Employee $employee )
    {   
       
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
        public function store(Request $request) {

             $request->validate([
                'category_name' => [
                    'required',
                    Rule::unique('categories', 'name'),
                ],
            ]);

            $category = new Category;
            $category->name = $request->category_name;
            $category->parent = $request->parent_category;
            if($category->save()){ 
                return response()->json(['class' => 'bg-success', 'error' => false, 'message' => 'Category Saved Successfully', 'call_back' => '', 'table_referesh' => true, 'model_id' => 'dataSave']);
            }
            return response()->json(['class' => 'bg-danger', 'error' => true, 'message' => 'Something went wrong', 'call_back' => '', 'table_referesh' => true, 'model_id' => 'dataSave']);
        }
        
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $category = Category::find($id);
        return view('admin.category.edit',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'category_name' => 'required|max:255|unique:categories,name,' . $id,
        ]);

        $category = Category::findOrFail($id);
        $category->name = $request->category_name;
        $category->parent = $request->parent_category;

        if ($category->save()) {
            return response()->json([
                'class' => 'bg-success',
                'error' => false,
                'message' => 'Category Updated Successfully',
                'call_back' => '',
                'table_referesh' => true,
                'model_id' => 'dataSave'
            ]);
        }

        return response()->json([
            'class' => 'bg-danger',
            'error' => true,
            'message' => 'Something went wrong',
            'call_back' => '',
            'table_referesh' => true,
            'model_id' => 'dataSave'
        ]);
    }


    public function updateParent(Request $request, $id)
    {
        $this->validate($request,[
            'parent_category'=>'required',     
            'child_category_id'=>'required',      
        ]);

        if(Category::where('id', $request->child_category_id)->update(['parent'=>$request->parent_category])){ 
            return response()->json(['message'=>'Category  Updated', 'class'=>'success', 'error'=>false]);
        }
        return response()->json(['class'=>'error','message'=>'Whoops, looks like something went wrong ! Try again ...']);
    }


    public function removeParent(Request $request, $id){

        if(Category::where('id', $id)->update(['parent'=>null])){ 
            return response()->json(['message'=>'Category  Updated', 'class'=>'success', 'error'=>false]);
        }
        return response()->json(['class'=>'error','message'=>'Whoops, looks like something went wrong ! Try again ...']);
    }


    public function parentList(Request $request){
        $categories = Category::all();
        if($categories->count()){ 
            return response()->json(['message'=>'Category  Updated', 'class'=>'success', 'error'=>false, 'datas'=>$categories]);
        }
        return response()->json(['class'=>'error','message'=>'Whoops, looks like something went wrong ! Try again ...']);
    }

    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Category $category)
    {
        Category::where('parent', $category->id)->update(['parent'=>null]);
        if($category->delete()){
            return response()->json(['message'=>'Category deleted Successfully ...', 'class'=>'success']);  
        }
        return response()->json(['message'=>'Whoops, looks like something went wrong ! Try again ...', 'class'=>'error']);
    }


    public function updateOrder(Request $request)
    {
        $order = json_decode($request->input('order'), true);
        $this->updateNodeOrder($order);
        return response()->json(['message'=>'Category Upfated Successfully ...', 'class'=>'bg-success', 'error' => false]);  
    }

    private function updateNodeOrder($nodes, $parentId = null)
    {
        foreach ($nodes as $index => $node) {
            Category::where('id', $node['id'])->update([
                'ordering' => $index,
                'parent' => $parentId
            ]);
            if (!empty($node['children'])) {
                $this->updateNodeOrder($node['children'], $node['id']);
            }
        }
    }


    public function changeOrder(Request $request){
        return $request->all();
        if($request->orderedNodes){
            $inputes = $request->orderedNodes;
            foreach($inputes as $input){
                $category = Category::find($input['id']);
               // $category->parent = $input['parent'];
                $category->ordering = $input['ordering'];
                $category->save();
            }
        } else{
            $category = Category::find($request->item_id);
            $category->parent = $request->item_parent;
            $category->ordering = $request->item_ordering;
            $category->save();
        }
        return $request->all();
        return response()->json(['message'=>'Category Updated Successfully ...', 'class'=>'bg-success', 'error'=>false]);   
    }


    public function renderCategories($categories)
    {
        echo '<ul>';
        foreach ($categories as $category) {
            echo '<li>' . $category->name . '</li>';

            if ($category->children->isNotEmpty()) {
                // Call the same function to render child categories
                $this->renderCategories($category->children);
            }
        }
        echo '</ul>';
    }


    public function renderCategoriesWithCheckbox($categories)
    {
        echo '<ul class="category-ul" data-bs-spy="scroll">';
        foreach ($categories as $category) {
            // Safely handle subCategory (parent) and grandparent IDs
            $parentId = $category->subCategory ? $category->subCategory->id : null;
            $grandparentId = $category->subCategory && $category->subCategory->subCategory ? $category->subCategory->subCategory->id : null;
    
            echo '<li>';
            echo '<span class="item">';
            echo '<label class="d-flex gap-2" for="cat-'.$category->id.'">';
            echo '<input id="cat-'.$category->id.'" type="checkbox" name="categories[]" value="' . $category->id . '" ';
            echo 'data-parent-id="' . ($parentId ?? '') . '" ';  // Output empty string if no parent
            echo 'data-grandparent-id="' . ($grandparentId ?? '') . '">';  // Output empty string if no grandparent
            echo $category->name . '</label>';
            echo '</span>';
    
            // Recursively render children if they exist
            if ($category->children->isNotEmpty()) {
                $this->renderCategoriesWithCheckbox($category->children);
            }
            echo '</li>';
        }
        echo '</ul>';
    }

    public function getAllParents($categories)
{
    $allParentIds = collect();

    foreach ($categories as $category) {
        // Recursively get all parent IDs for this category
        $parentIds = $this->getCategoryParents($category);
        $allParentIds = $allParentIds->merge($parentIds);
    }

    return $allParentIds->unique();
}

// Helper function to get the parent chain of a category
private function getCategoryParents($category)
{
    $parents = collect();
    while ($category->subCategory) {
        $parents->push($category->subCategory->id);
        $category = $category->subCategory;
    }
    return $parents;
}
}
