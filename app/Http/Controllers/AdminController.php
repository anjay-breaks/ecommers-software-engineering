<?php

namespace App\Http\Controllers;
use App\Models\Brand;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Filesystem\Filesystem;
use Carbon\Carbon;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Slide;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
   public function index()
{
    $user = Auth::user();

    // Ambil 10 order terakhir
    $orders = Order::orderBy('created_at', 'DESC')->take(10)->get();

    // Data untuk ringkasan dashboard
    $dashboardDatas = DB::select("
        SELECT
            SUM(total) AS TotalAmount,
            SUM(IF(status='ordered', total, 0)) AS TotalOrderedAmount,
            SUM(IF(status='delivered', total, 0)) AS TotalDeliveredAmount,
            SUM(IF(status='canceled', total, 0)) AS TotalCanceledAmount,
            COUNT(*) AS Total,
            SUM(IF(status='ordered', 1, 0)) AS TotalOrdered,
            SUM(IF(status='delivered', 1, 0)) AS TotalDelivered,
            SUM(IF(status='canceled', 1, 0)) AS TotalCanceled
        FROM orders
    ");

    $monthlyDatas = DB::select("
        SELECT M.id AS MonthNo, M.name AS MonthName,
            IFNULL(D.TotalAmount, 0) AS TotalAmount,
            IFNULL(D.TotalOrderedAmount, 0) AS TotalOrderedAmount,
            IFNULL(D.TotalDeliveredAmount, 0) AS TotalDeliveredAmount,
            IFNULL(D.TotalCanceledAmount, 0) AS TotalCanceledAmount
        FROM month_names M
        LEFT JOIN (
            SELECT DATE_FORMAT(created_at, '%b') AS MonthName,
                MONTH(created_at) AS MonthNo,
                SUM(total) AS TotalAmount,
                SUM(IF(status='ordered', total, 0)) AS TotalOrderedAmount,
                SUM(IF(status='delivered', total, 0)) AS TotalDeliveredAmount,
                SUM(IF(status='canceled', total, 0)) AS TotalCanceledAmount
            FROM orders
            WHERE YEAR(created_at) = YEAR(NOW())
            GROUP BY YEAR(created_at), MONTH(created_at), DATE_FORMAT(created_at, '%b')
            ORDER BY MONTH(created_at)
        ) D ON D.MonthNo = M.id
    ");

    $AmountM = implode(',', collect($monthlyDatas)->pluck('TotalAmount')->toArray());
    $OrderedAmountM = implode(',', collect($monthlyDatas)->pluck('TotalOrderedAmount')->toArray());
    $DeliveredAmountM = implode(',', collect($monthlyDatas)->pluck('TotalDeliveredAmount')->toArray());
    $CanceledAmountM = implode(',', collect($monthlyDatas)->pluck('TotalCanceledAmount')->toArray());

    $TotalAmount = collect($monthlyDatas)->sum('TotalAmount');
    $TotalOrderedAmount = collect($monthlyDatas)->sum('TotalOrderedAmount');
    $TotalDeliveredAmount = collect($monthlyDatas)->sum('TotalDeliveredAmount');
    $TotalCanceledAmount = collect($monthlyDatas)->sum('TotalCanceledAmount');

    // Cek apakah CEO atau bukan
    if ($user->utype === 'CEO') {
        return view('ceo.index', compact(
            'orders', 'dashboardDatas',
            'AmountM', 'OrderedAmountM', 'DeliveredAmountM', 'CanceledAmountM',
            'TotalAmount', 'TotalOrderedAmount', 'TotalDeliveredAmount', 'TotalCanceledAmount'
        ));
    } elseif($user->utype === 'ADM') {
        return view('admin.index', compact(
            'orders', 'dashboardDatas',
            'AmountM', 'OrderedAmountM', 'DeliveredAmountM', 'CanceledAmountM',
            'TotalAmount', 'TotalOrderedAmount', 'TotalDeliveredAmount', 'TotalCanceledAmount'
        ));
    }
}


    public function brands(){
        $brands = Brand::orderBy('id','DESC')->paginate(10);
        return view('admin.brands',compact('brands'));
    }
    public function add_brand(){
        return view('admin.brand-add');
    }
    public function brand_store(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);
        $brand= new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $image = $request->file('image');
        $file_extention = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp.'.'.$file_extention;
        $this->GenerateBrandThumbailsImage($image,$file_name);
        $brand->image = $file_name;
        $brand->save();
        return redirect()->route('admin.brands')->with('status','Brand has been added succesfully!');
    }
    public function brand_edit($id){
        $brand = Brand::find($id);
        return view('admin.brand-edit',compact('brand'));

    }
    public function brand_update(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);
        $brand = Brand::find($request->id);
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        if($request->hasfile('image')){
            if(File::exists(public_path('uploads/brands').'/'.$brand->image)){
                File::delete(public_path('uploads/brands').'/'.$brand->image);
            }
            $image = $request->file('image');
            $file_extention = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extention;
            $this->GenerateBrandThumbailsImage($image,$file_name);
            $brand->image = $file_name;
        }

        $brand->save();
        return redirect()->route('admin.brands')->with('status','Brand has been updated succesfully!');
    }
    public function GenerateBrandThumbailsImage($image,$imageName){
        $destinationPath = public_path('uploads/brands');
        $img = Image::read($image->path());
        $img->cover(124,124,"top");
        $img->resize(124,124,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);
    }
    public function brand_delete($id){
        $brand = Brand::find($id);
        if(File::exists(public_path('uploads/brands').'/'.$brand->image)){
            File::delete(public_path('uploads/brands').'/'.$brand->image);
        }
        $brand->delete();
        return redirect()->route('admin.brands')->with('status','Brand has been deleted successfully!');
    }
    public function categories(){
        $categories = Category::orderBy('id','DESC')->paginate(10);
        return view('admin.categories',compact('categories'));
    }
    public function category_add(){
        return view('admin.category-add');
    }
    public function category_store(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);
        $category = new Category();
        $category ->name = $request->name;
        $category ->slug = Str::slug($request->name);
        $image = $request->file('image');
        $file_extention = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp.'.'.$file_extention;
        $this->GenerateCategoryThumbailsImage($image,$file_name);
        $category ->image = $file_name;
        $category ->save();
        return redirect()->route('admin.categories')->with('status','Category has been added succesfully!');
    }
    public function GenerateCategoryThumbailsImage($image,$imageName){
        $destinationPath = public_path('uploads/categories');
        $img = Image::read($image->path());
        $img->cover(124,124,"top");
        $img->resize(124,124,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);
    }
    public function category_edit($id){
        $category = Category::find($id);
        return view('admin.category-edit',compact('category'));
    }
    public function category_update(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$request->id,
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);
        $category = Category::find($request->id);
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        if($request->hasfile('image')){
            if(File::exists(public_path('uploads/categories').'/'.$category->image)){
                File::delete(public_path('uploads/categories').'/'.$category->image);
            }
            $image = $request->file('image');
            $file_extention = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extention;
            $this->GenerateCategoryThumbailsImage($image,$file_name);
            $category->image = $file_name;
        }

        $category->save();
        return redirect()->route('admin.categories')->with('status','category has been updated succesfully!');
    }
    public function category_delete($id){
        $category = Category::find($id);
        if(File::exists(public_path('uploads/categories').'/'.$category->image)){
            File::delete(public_path('uploads/categories').'/'.$category->image);
        }
        $category->delete();
        return redirect()->route('admin.categories')->with('status','Category has been deleted successfully!');

    }
    public function products(){
        $products = Product::orderBy('created_at','DESC')->paginate(10);
        return view('admin.products',compact('products'));
    }
    public function products_add(){
        $categories = Category::select('id','name')->orderBy('name')->get();
        $brands = Brand::select('id','name')->orderBy('name')->get();
        return view('admin.products-add',compact('categories','brands'));

    }
    public function products_store(Request $request){
        $request->validate([
            'name'=> 'required',
            'slug'=> 'required|unique:products,slug',
            'short_description'=> 'required',
            'description'=> 'required',
            'regular_price'=> 'required',
            'sale_price'=> 'required',
            'SKU'=> 'required',
            'stock_status'=> 'required',
            'featured'=> 'required',
            'quantity'=> 'required',
            'image'=> 'required|mimes:png,jpg,jpeg|max:2048',
            'category_id'=> 'required',
            'brand_id'=> 'required'
        ]);
        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->image = $request->image;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        $current_timestamp = Carbon::now()->timestamp;

        if($request->hasFile('image')){
            $image =$request->file('image');
            $imageName =$current_timestamp.'.'.$image->extension();
            $this->GenerateProductThumbnailImage($image,$imageName);
            $product->image = $imageName;
        }
        $galery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if($request->hasFile('image')){
            $allowedfileExtion = ['jpg','png','jpeg'];
            $files = $request->file('images');
            foreach($files as $file){
                $gextension = $file->getClientOriginalExtension();
                $gcheck = in_array($gextension,$allowedfileExtion);
                if($gcheck){
                    $gfileName =$current_timestamp ."-" . $counter . "." . $gextension;
                    $this->GenerateProductThumbnailImage($file,$gfileName);
                    array_push( $galery_arr,  $gfileName);
                    $counter = $counter + 1;
                }
            }
            $galery_images = implode(',',$galery_arr);
        }
        $product->images = $gallery_images;
        $product->save();
        return redirect()->route('admin.products')->with('status','Product has been added successfully!');
    }
    public function GenerateProductThumbnailImage($image,$imageName){
        $destinationPathThunbnail = public_path('uploads/products/thumbnails');
        $destinationPath = public_path('uploads/products');
        $img = Image::read($image->path());

        $img->cover(540,689,"top");
        $img->resize(540,689,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);

        $img->resize(104,104,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPathThunbnail.'/'.$imageName);
    }
    public function product_edit($id){
        $product = Product::find($id);
        $categories = Category::select('id','name')->orderBy('name')->get();
        $brands = Brand::select('id','name')->orderBy('name')->get();
        return view('admin.product-edit',compact('product','categories','brands'));
    }
    public function product_update(Request $request){
        $request->validate([
            'name'=> 'required',
            'slug'=> 'required|unique:products,slug,'.$request->id,
            'short_description'=> 'required',
            'description'=> 'required',
            'regular_price'=> 'required',
            'sale_price'=> 'required',
            'SKU'=> 'required',
            'stock_status'=> 'required',
            'featured'=> 'required',
            'quantity'=> 'required',
            'image'=> 'mimes:png,jpg,jpeg|max:2048',
            'category_id'=> 'required',
            'brand_id'=> 'required'
        ]);
        $product = Product::find($request->id );
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->image = $request->image;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        $current_timestamp = Carbon::now()->timestamp;
        if($request->hasFile('image')){
            if(File::exists(public_path('uploads/products').'/'.$product->image)){
                File::delete(public_path('uploads/products').'/'.$product->image);
            }
            if(File::exists(public_path('uploads/products/thumbnails').'/'.$product->image)){
                File::delete(public_path('uploads/products/thumbnails').'/'.$product->image);
            }
            $image =$request->file('image');
            $imageName =$current_timestamp.'.'.$image->extension();
            $this->GenerateProductThumbnailImage($image,$imageName);
            $product->image = $imageName;
        }
        $galery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if($request->hasFile('image')){
            foreach(explode(',',$product->images) as $ofile){
                if(File::exists(public_path('uploads/products').'/'.$ofile)){
                    File::delete(public_path('uploads/products').'/'.$ofile);
                }
                if(File::exists(public_path('uploads/products/thumbnails').'/'.$ofile)){
                    File::delete(public_path('uploads/products/thumbnails').'/'.$ofile);
                }
            }
            $allowedfileExtion = ['jpg','png','jpeg'];
            $files = $request->file('images');
            foreach($files as $file){
                $gextension = $file->getClientOriginalExtension();
                $gcheck = in_array($gextension,$allowedfileExtion);
                if($gcheck){
                    $gfileName =$current_timestamp ."-" . $counter . "." . $gextension;
                    $this->GenerateProductThumbnailImage($file,$gfileName);
                    array_push( $galery_arr,  $gfileName);
                    $counter = $counter + 1;
                }
            }
            $galery_images = implode(',',$galery_arr);
        }
        $product->images = $gallery_images;
        $product->save();
        return redirect()->route('admin.products')->with('status','Product has been updated successfully!');
    }
    public function product_delete($id){
        $product = Product::find($id);
        if(File::exists(public_path('uploads/products').'/'.$product->image)){
            File::delete(public_path('uploads/products').'/'.$product->image);
        }
        if(File::exists(public_path('uploads/products/thumbnails').'/'.$product->image)){
            File::delete(public_path('uploads/products/thumbnails').'/'.$product->image);
        }
        foreach(explode(',',$product->images) as $ofile){
            if(File::exists(public_path('uploads/products').'/'.$ofile)){
                File::delete(public_path('uploads/products').'/'.$ofile);
            }
            if(File::exists(public_path('uploads/products/thumbnails').'/'.$ofile)){
                File::delete(public_path('uploads/products/thumbnails').'/'.$ofile);
            }
        }
        $product->delete();
        return redirect()->route('admin.products')->with('status','Product has been deleted successfully!');
    }
    public function coupons(){
       // $coupons = Coupon::orderBy('expiry_date','DESC')->paginate(12);
       $coupons = Coupon::orderBy('id', 'ASC')->paginate(12);
       //$coupons = Coupon::orderBy('expiry_date', 'ASC')->orderBy('id', 'ASC')->paginate(12);
        return view('admin.coupons',compact('coupons'));
    }
    public function coupon_add(){

        return view('admin.coupon-add');
    }
    public function coupon_store(Request $request){

        $request->validate([
            'code' => 'required',
            'type' => 'required',
            'value' => 'required|numeric',
            'cart_value' => 'required|numeric',
            'expiry_date' => 'required|date',
        ]);
        $coupon = new Coupon();
        $coupon->code= $request->code;
        $coupon->type= $request->type;
        $coupon->value= $request->value;
        $coupon->cart_value= $request->cart_value;
        $coupon->expiry_date= $request->expiry_date;
        $coupon->save();
        return redirect()->route('admin.coupons')->with('status','Coupon has been added successfully!');

    }
    public function coupon_edit($id){
        $coupon = Coupon::find($id);
        return view('admin.coupon-edit',compact('coupon'));

    }
    public function coupon_update(Request $request){
           $request->validate([
            'code' => 'required',
            'type' => 'required',
            'value' => 'required|numeric',
            'cart_value' => 'required|numeric',
            'expiry_date' => 'required|date',
        ]);
        $coupon =Coupon::find($request->id);
        $coupon->code= $request->code;
        $coupon->type= $request->type;
        $coupon->value= $request->value;
        $coupon->cart_value= $request->cart_value;
        $coupon->expiry_date= $request->expiry_date;
        $coupon->save();
        return redirect()->route('admin.coupons')->with('status','Coupon has been updated successfully!');
    }
    public function coupon_delete($id){
         $coupon =Coupon::find($id);
         $coupon->delete();
         return redirect()->route('admin.coupons')->with('status','Coupon has been deleted successfully!');
    }
    public function orders(){
        $orders = Order::orderBy('created_at','DESC')->paginate(12);
        return view('admin.orders',compact('orders'));
    }
    public function order_details($order_id){
        $order = Order::find($order_id);
        $orderItems = OrderItem::where('order_id',$order_id)->orderBy('id')->paginate(12);
        $transaction = Transaction::where('order_id',$order_id)->first();
        return view('admin.order-details',compact('order','orderItems','transaction'));
    }
    public function update_order_status(Request $request){
        $order = Order::find($request->order_id);
        $order->status = $request->order_status;
        if($request->order_status == 'delivered'){
            $order->delivered_date = Carbon::now();
        }
        else if($request->order_status == 'canceled'){
            $order->canceled_date = Carbon::now();
        }
        $order->save();
        if($request->order_status =='delivered'){
            $transaction = Transaction::where('order_id',$request->order_id)->first();
            $transaction->status = 'approved';
            $transaction->save();
        }
        return back()->with("status","Status changed successfully! ");
    }
    public function slides(){
        $slides = Slide::orderBy('id','DESC')->paginate(12);
        return view('admin.slides',compact('slides'));
    }
    public function slide_add(){
        return view('admin.slide-add');
    }
    public function slide_store(Request $request){
        $request->validate([
            'tagline'=>'required',
            'title'=>'required',
            'subtitle'=>'required',
            'link'=>'required',
            'status'=>'required',
            'image'=>'required|mimes:png,jpg,jpeg|max:2048'
        ]);
        $slide = new Slide();
        $slide->tagline = $request->tagline;
        $slide->title = $request->title;
        $slide->subtitle = $request->subtitle;
        $slide->link = $request->link;
        $slide->status = $request->status;

        $image = $request->file('image');
        $file_extention = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp.'.'.$file_extention;
        $this->GenerateSlideThumbailsImage($image,$file_name);
        $slide ->image = $file_name;
        $slide->save();
        return redirect()->route('admin.slides')->with("status","Slide added successfully!");
    }
    public function GenerateSlideThumbailsImage($image,$imageName){
        $destinationPath = public_path('uploads/slides');
        $img = Image::read($image->path());
        $img->cover(400,600,"top");
        $img->resize(400,600,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);
    }
    public function slide_edit($id){
        $slide = Slide::find($id);
        return view('admin.slide-edit',compact('slide'));
    }
    public function slide_update(Request $request){
         $request->validate([
            'tagline'=>'required',
            'title'=>'required',
            'subtitle'=>'required',
            'link'=>'required',
            'status'=>'required',
            'image'=>'mimes:png,jpg,jpeg|max:2048'
        ]);
        $slide = Slide::find($request->id);
        $slide->tagline = $request->tagline;
        $slide->title = $request->title;
        $slide->subtitle = $request->subtitle;
        $slide->link = $request->link;
        $slide->status = $request->status;

        if($request->hasFile('image'))
        {
            if(File::exists(public_path('uploads/slides').'/'.$slide->image))
            {
                File::delete(public_path('uploads/slides').'/'.$slide->image);
            }
            $image = $request->file('image');
            $file_extention = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extention;
            $this->GenerateSlideThumbailsImage($image,$file_name);
            $slide ->image = $file_name;
        }
        $slide->save();
        return redirect()->route('admin.slides')->with("status","Slide has benn updated successfully!");
    }
    public function slide_delete($id){
        $slide = Slide::find($id);
        if(File::exists(public_path('uploads/slides').'/'.$slide->image))
        {
            File::delete(public_path('uploads/slides').'/'.$slide->image);
        }
        $slide->delete();
        return redirect()->route('admin.slides')->with("status"."Slid lah eh hapus");
    }

    public function contacts()
    {
        $contacts = Contact::orderBy('created_at','DESC')->paginate(10);
        return view('admin.contacts',compact('contacts'));
    }
    public function contact_delete($id)
    {
        $contact = Contact::find($id);
        $contact->delete();
        return redirect()->route('admin.contacts')->with("status","Contact delete success mas");
    }
    public function search(Request $request)
    {
        $query =$request->input('query');
        $results =Product::where('name','LIKE',"%{$query}%")->get()->take(8);
        return response()->json($results);
    }

    public function profil()
    {
        return view('admin.account');
    }

public function edit(Request $request)
{
    $request->validate([
        'name' => 'string|max:40',
        'mobile' => 'digits:12',
        'email' => 'email',
        'password' => 'nullable|string|min:6',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $user = Auth::user();

    // Update field
    $user->name = $request->name;
    $user->mobile = $request->mobile;
    $user->email = $request->email;

    // Update password hanya jika diisi
    if ($request->filled('password')) {
        $user->password = bcrypt($request->password);
    }

    $user->save();

    // Upload dan simpan foto jika ada
    if ($request->hasFile('photo')) {
        $file = $request->file('photo');
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public/user_photos', $filename);

        // Nonaktifkan foto profil sebelumnya
        $user->photos()->update(['is_profile' => false]);

        // Simpan foto baru sebagai profil
        $user->photos()->create([
            'photo_path' => $path,
            'is_profile' => true,
        ]);
    }

    return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
}


    public function listUsers()
    {
    $users = User::all();
    return view('ceo.ubah-role', compact('users'));
    }
    public function updateUserRole(Request $request, $id)
    {
    if (Auth::user()->utype !== 'CEO') {
        abort(403, 'Hanya CEO yang dapat mengubah role.');
    }

    $request->validate([
        'role' => 'required|in:ADM,CEO,USR',
    ]);

    $user = User::findOrFail($id);
    $user->utype = $request->role;
    $user->save();

    return redirect()->back()->with('success', 'Role berhasil diperbarui.');
    }


}
