<?php

namespace NoiThatZip\Attachment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use NoiThatZip\Attachment\Models\Attachment;

class AttachmentController extends Controller
{
    protected $user;

    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
        $this->middleware(['role_or_permission:qa|delete videos']);
    }

    public function destroy($id)
    {
        $video = Attachment::findOrFail($id);
        $video->delete();
        return ['message' => "Xoa thanh cong!"];
    }
}
