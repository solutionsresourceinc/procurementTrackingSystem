<?php

class PurchaseRequestController extends Controller
{
    public function view()
    {
        $link = "completeTable/active";
        $pageName = "List of Active Purchase Requests";
        $date_today =date('Y-m-d H:i:s');
        $searchBy = '0';
        if(Entrust::hasRole('Requisitioner'))
        {
            $requests = DB::table('purchase_request')->where('office', '=', Auth::user()->office_id)->where('dueDate','>',$date_today)->where('status', '=', 'Active')->orderBy('dateReceived', 'DESC');

            $pageCounter = $requests->count();
            $requests = $requests->get();
        }
        else
        {
            $requests = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'Active')->orderBy('dateReceived', 'DESC');
            $pageCounter = $requests->count();
            $requests = $requests->get();
        }
        return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName)->with('link',$link);
    }

    public function viewClosed()
    {
        $pageName = "List of Closed Purchase Requests";
        $date_today =date('Y-m-d H:i:s');
        $searchBy = '0';
        if(Entrust::hasRole('Requisitioner'))
        {
            $requests = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('office', Auth::user()->office_id)->where('status', '=', 'Closed')->orderBy('dateReceived', 'DESC');
            $pageCounter = $requests->count();
            $requests = $requests->get();
        }
        else
        {
            $requests = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'Closed')->orderBy('dateReceived', 'DESC');
            $pageCounter = $requests->count();
            $requests = $requests->get();
        }
        return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName)->with('link', 'completeTable/closed');
    }

    public function viewOverdue()
    {
        // return View::make('purchaseRequest.purchaseRequest_overdue');
        $pageName = "List of Overdue Purchase Requests";
        $date_today = date('Y-m-d H:i:s');
        $searchBy = '0';
        if(Entrust::hasRole('Requisitioner'))
        {
            $requests = DB::table('purchase_request')->where('dueDate','<=',$date_today)->where('office', Auth::user()->office_id)->where('status', '=', 'Active')->orderBy('dateReceived', 'DESC');
            $pageCounter = $requests->count();
            $requests = $requests->get();
        }
        else
        {
            $requests = DB::table('purchase_request')->where('dueDate','<=',$date_today)->where('status', '=', 'Active')->orderBy('dateReceived', 'DESC');
            $pageCounter = $requests->count();
            $requests = $requests->get();
        }
        return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName)->with('link', 'completeTable/overdue');
    }

    public function viewCancelled()
    {
        $pageName = "List of Cancelled Purchase Requests";
        $date_today =date('Y-m-d H:i:s');
        $searchBy = '0';
        if(Entrust::hasRole('Requisitioner'))
        {
            $requests = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('office', Auth::user()->office_id)->where('status', '=', 'Cancelled')->orderBy('dateReceived', 'DESC');
            $pageCounter = $requests->count();
            $requests = $requests->get();
        }
        else
        {
            $requests = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'Cancelled')->orderBy('dateReceived', 'DESC');
            $pageCounter = $requests->count();
            $requests = $requests->get();
        }
        return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName)->with('link', 'completeTable/cancelled');
    }

    public function create()
    {
        $office = Office::all();
        $users = User::all();
        $workflow = Workflow::all();
        return View::make('purchaseRequest.purchaseRequest_create')
            ->with('office',$office)
            ->with('users',$users)
            ->with('workflow',$workflow);
    }

    public function autoupload()
    {
        //Image Upload
        $purchasecheck=Purchase::orderby('id', 'DESC')->count();
        if($purchasecheck!=0)
        {
            $purchase=Purchase::orderby('id', 'DESC')->first();
            $docs=Document::orderby('id', 'DESC')->first();
            $pr_id=$purchase->id+1;
            $doc_id=$docs->id+1;
        }
        else
        {
            $pr_id=1;
            $doc_id=1;
        }

        foreach(Input::file('file') as $file)
        {
            $rules = array(
                'file' => 'required|mimes:png,gif,jpeg,jpg|max:900000000000000000000'
            );

            $validator = \Validator::make(array('file'=> $file), $rules);
            $destine = public_path()."/uploads";
            if($validator->passes())
            {
                $ext = $file->guessClientExtension(); // (Based on mime type)
                $ext = $file->getClientOriginalExtension(); // (Based on filename)
                $filename = $file->getClientOriginalName();

                $archivo = value(function() use ($file)
                {
                    $filename = str_random(10) . '.' . $file->getClientOriginalExtension();
                    return strtolower($filename);
                });

                $archivo = value(function() use ($file)
                {
                    $date = date('m-d-Y-h-i-s', time());
                    $filename = $date."-". $file->getClientOriginalName();
                    return strtolower($filename);
                });

                $attach = new Attachments;
                $attach->doc_id=$doc_id;
                $attach->data = $archivo;
                $attach->save();

                $filename = $doc_id."_".$attach->id;
                $file->move($destine, $archivo);

                $target_folder = $destine;
                $upload_image = $target_folder."/".$archivo;

                $thumbnail = $target_folder."/resize".$archivo;
                $actual = $target_folder."/".$archivo;

                // THUMBNAIL SIZE
                list($width, $height) = getimagesize($upload_image);

                $newwidth = $width;
                $newheight = $height;
                while ( $newheight > 525)
                {
                    $newheight=$newheight*0.8;
                    $newwidth=$newwidth*0.8;
                }

                $source=$upload_image;
                $ext  = strtolower($ext);
                $thumb = imagecreatetruecolor($newwidth, $newheight);
                if ($ext=="jpg"||$ext=="jpeg")
                    $source = imagecreatefromjpeg($upload_image);
                elseif ($ext=="png")
                    $source = imagecreatefrompng($upload_image);
                elseif ($ext=="gif")
                    $source = imagecreatefromgif($upload_image);
                else
                    continue;

                // RESIZE
                imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                // MAKE NEW FILES
                if ($ext=="jpg"||$ext=="jpeg")
                    imagejpeg($thumb, $thumbnail, 100);
                elseif ($ext=="png")
                    imagepng($thumb, $thumbnail, 9);
                elseif($ext=="gif")
                    imagegif($thumb, $thumbnail, 100);
                else
                    echo "An invalid image";

                unlink($actual);
                // FILE RENAMES
                rename($thumbnail, $actual);
            }
            else
            {

                Session::put('imgerror','Invalid file.');
            }
        }
        //End Image Upload
        return redirect::back()->withInput();
    }

    public function create_submit()
    {
        //Image Upload
        $users = DB::table('users')->get();

        $purchasecheck = DB::table('purchase_request')->count();
        if($purchasecheck!=0)
        {
            $purchase=Purchase::orderby('id', 'DESC')->first();
            $docs=Document::orderby('id', 'DESC')->first();
            $pr_id=$purchase->id+1;
            $doc_id=$docs->id+1;
        }
        else
        {
            $pr_id=1;
            $doc_id=1;
        }

        foreach(Input::file('file') as $file)
        {
            $rules = array(
                'file' => 'required|mimes:png,gif,jpeg,jpg|max:900000000000000000000'
            );

            $validator = \Validator::make(array('file'=> $file), $rules);
            $destine = public_path()."/uploads";
            if($validator->passes())
            {
                $ext = $file->guessClientExtension(); // (Based on mime type)
                $ext = $file->getClientOriginalExtension(); // (Based on filename)
                $filename = $file->getClientOriginalName();

                $archivo = value(function() use ($file)
                {
                    $filename = str_random(10) . '.' . $file->getClientOriginalExtension();
                    return strtolower($filename);
                });

                $archivo = value(function() use ($file)
                {
                    $date = date('m-d-Y-h-i-s', time());
                    $filename = $date."-". $file->getClientOriginalName();
                    return strtolower($filename);
                });

                $attach = new Attachments;
                $attach->doc_id=$doc_id;
                $attach->data = $archivo;
                $attach->save();

                $filename = $doc_id."_".$attach->id;
                $file->move($destine, $archivo);

                $target_folder = $destine;
                $upload_image = $target_folder."/".$archivo;

                $thumbnail = $target_folder."/resize".$archivo;
                $actual = $target_folder."/".$archivo;

                // THUMBNAIL SIZE
                list($width, $height) = getimagesize($upload_image);

                $newwidth = $width;
                $newheight = $height;
                while ( $newheight > 525)
                {
                    $newheight=$newheight*0.8;
                    $newwidth=$newwidth*0.8;
                }

                $source=$upload_image;
                $ext  = strtolower($ext);
                $thumb = imagecreatetruecolor($newwidth, $newheight);
                if ($ext=="jpg"||$ext=="jpeg")
                    $source = imagecreatefromjpeg($upload_image);
                elseif ($ext=="png")
                    $source = imagecreatefrompng($upload_image);
                elseif ($ext=="gif")
                    $source = imagecreatefromgif($upload_image);
                else
                    continue;

                // RESIZE
                imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                // MAKE NEW FILES
                if ($ext=="jpg"||$ext=="jpeg")
                    imagejpeg($thumb, $thumbnail, 100);
                elseif ($ext=="png")
                    imagepng($thumb, $thumbnail, 9);
                elseif($ext=="gif")
                    imagegif($thumb, $thumbnail, 100);
                else
                    echo "An invalid image";

                unlink($actual);
                // FILE RENAMES
                rename($thumbnail, $actual);
            }
            else
            {
                $errors = $validator->errors();
                Session::put('imgerror','Invalid file.');
            }
        }
        //End Image Upload

        $purchase = new Purchase;
        $document = new Document;
        $purchase->projectPurpose = strip_tags(Input::get( 'projectPurpose' ));
        $purchase->sourceOfFund = strip_tags(Input::get( 'sourceOfFund' ));
        $purchase->amount = Input::get( 'amount' );
        $purchase->office = Input::get( 'office' );
        $purchase->requisitioner = Input::get( 'requisitioner' );

        if(!Input::get('dateRequested') == '')
                $purchase->dateRequested = Input::get( 'dateRequested' );

        $purchase->dateReceived = Input::get( 'dateReceived' );
        $purchase->status = 'Active';
        $purchase->otherType = Input::get('otherType');

        // Get latest control number
        $cn = Input::get('controlNo');
        $checkUniqueControlNo = Purchase::where('controlNo','=',"$cn")->count();
        if($checkUniqueControlNo != 0)
        {
            $purchase->controlNo = '';
        }
        else
        {
            $purchase->controlNo = Input::get('controlNo');
        }

        if(Input::get('otherType') == 'Pakyaw')
            $purchase->projectType = "None";
        else
            $purchase->projectType = Input::get('projectType');

        // Set creator id
        $user_id = Auth::user()->id;
        $purchase->created_by = $user_id;

        $purchase_save = $purchase->save();

        if($purchase_save&&(Session::get('imgerror')==NULL||!Input::hasfile('file')))
        {
            $document->pr_id = $purchase->id;
            $document->work_id = Input::get('hide_modeOfProcurement');
            $document_save = $document->save();

            if($document_save)
            {
                $doc_id= $document->id;
                $workflow=Workflow::find($document->work_id);
                $section=Section::where('workflow_id',$document->work_id)->orderBy('section_order_id', 'ASC')->get();
                $firstnew=0;

                // Set due date;
                $new_purchase = Purchase::find($purchase->id);
                $workflow_id = Input::get('hide_modeOfProcurement');
                $workflow = Workflow::find($workflow_id);
                $addToDate = $workflow->totalDays;
                date_default_timezone_set("Asia/Manila");
                $dueDate = date('Y-m-d H:i:s', strtotime("+$addToDate days" ));
                $new_purchase->dueDate = $dueDate;
                $new_purchase->save();

                $tasks = Task::where('wf_id', $document->work_id)->orderBy('section_id', 'ASC')->orderBy('order_id', 'ASC')->get();

                foreach ($tasks as $task)
                {
                    $task_details = New TaskDetails;
                    $task_details->task_id = $task->id;
                    $stringamount=$new_purchase->amount;
                    $stringamount=str_replace(str_split(','), '', $stringamount);
                    $amount = (float)$stringamount;

                    if($firstnew == 0)
                        $task_details->status = "New";
                    else
                        $task_details->status = "Pending";

                    //Project Type
                    if($task->taskName=="PRE-PROCUREMENT CONFERENCE"||$task->taskName=="ADVERTISEMENT"||$task->taskName=="PRE-BID CONFERENCE")
                    {
                        $task_details->status="Lock";
                        if($new_purchase->projectType=="Goods/Services")
                        {
                            if($task->taskName=="PRE-PROCUREMENT CONFERENCE"||$task->taskName=="ADVERTISEMENT")
                            {
                                if(($amount>2000000))
                                {
                                    $task_details->status="Pending";
                                }
                            }
                            else if($task->taskName=="PRE-BID CONFERENCE")
                            {
                                if(($amount>1000000))
                                {
                                    $task_details->status="Pending";
                                }
                            }
                        }
                        elseif($new_purchase->projectType=="Infrastructure")
                        {
                            if($task->taskName=="PRE-PROCUREMENT CONFERENCE"||$task->taskName=="ADVERTISEMENT")
                            {
                                if(($amount>5000000))
                                {
                                    $task_details->status="Pending";
                                }
                            }
                            else if($task->taskName=="PRE-BID CONFERENCE")
                            {
                                if(($amount>1000000))
                                {
                                    $task_details->status="Pending";
                                }
                            }
                        }
                        elseif($new_purchase->projectType=="Consulting Services")
                        {
                            if(($amount>1000000))
                            {
                                $task_details->status="Pending";
                            }
                        }
                    }
                    //End Project Type
                    $firstnew=1;
                    $task_details->doc_id = $document->id;
                    $task_details->save();
                }

                $users = User::all();
                foreach($users as $user)
                {
                    $count = new Count;
                    $count->user_id = $user->id;
                    $count->doc_id = $doc_id;
                    $count->save();
                }

                $pr_id = Session::get('pr_id');

                DB::table('attachments')->where('doc_id', $doc_id)->update(array( 'saved' => 1));
                DB::table('attachments')->where('saved', '=', 0)->delete();

                Session::forget('doc_id');
                if(!Input::hasfile('file'))
                {
                    Session::forget('imgerror');
                }

                $connected = @fsockopen("www.google.com", 80);  //website, port  (try 80 or 443)
                if ($connected)
                {
                    $sendee = DB::table('users')->where('id',$purchase->requisitioner)->first();
                    $email = $sendee->email;
                    $fname = $sendee->firstname;

                    $data = [ 'id' => $purchase->id ];
                    Mail::send('emails.template', $data, function($message) use($email, $fname)
                    {
                        $message->from('procurementTrackingSystem@tarlac.com', 'Procurement Tracking System Tarlac');
                        $message->to($email, $fname)->subject('Tarlac Procurement Tracking System: New Purchase Request Created');
                    });

                    $notice = "Purchase request created successfully. ";
                }
                else
                {
                    $notice = "Purchase request created successfully. Email notice was not sent. ";
                }

                // Insert data to reports table
                $date_received = Input::get( 'dateReceived' );
                $date_received = substr($date_received, 0, strrpos($date_received, ' '));
                $reports = Reports::whereDate($date_received)->first();

                if($reports == null)
                {
                    $reports = new Reports;
                    $reports->date = $date_received;
                    $reports->pRequestCount = 1;
                }
                else
                {
                    $reports->pRequestCount = $reports->pRequestCount + 1;
                }
                $reports->save();
                //End Reports

                Session::put('notice', $notice);
                $office = Office::all();
                $users = User::all();
                $workflow = Workflow::all();

                return Redirect::to('purchaseRequest/view');
                Session::put('imgsuccess','Files uploaded.');
            }
            else
            {
                $message = "Failed to create purchase request.";
                Session::put('main_error', $message );

                // Get Other Error Messages
                $error_projectPurpose = $purchase->validationErrors->first('projectPurpose');
                $error_projectType = $purchase->validationErrors->first('projectType');
                $error_sourceOfFund = $purchase->validationErrors->first('sourceOfFund');
                $error_amount = $purchase->validationErrors->first('amount');
                $error_office = $purchase->validationErrors->first('office');
                $error_requisitioner = $purchase->validationErrors->first('requisitioner');
                $error_dateRequested = $purchase->validationErrors->first('dateRequested');
                $error_dateReceived = $purchase->validationErrors->first('dateReceived');
                $error_controlNo = $purchase->validationErrors->first('controlNo');

                // Inserting Error Message To a Session
                Session::put('error_projectPurpose', $error_projectPurpose );
                Session::put('error_sourceOfFund', $error_sourceOfFund );
                Session::put('error_amount', $error_amount );
                Session::put('error_office', $error_office );
                Session::put('error_requisitioner', $error_requisitioner );
                Session::put('error_dateRequested', $error_dateRequested );
                Session::put('error_dateReceived', $error_dateReceived );
                Session::put('error_projectType', $error_projectType );
                Session::put('error_controlNo', $error_controlNo );

                if($checkUniqueControlNo != 0)
                {
                    Session::put('error_controlNo', 'This control no. already exists. Please enter a new one.' );
                }

                if(Input::get('hide_modeOfProcurement') == "")
                {
                    Session::put('m6', 'required' );
                }

                Session::put('imgsuccess','Files uploaded.');
                return Redirect::back()->withInput();
            }
        }
        else
        {
            // Set Main Error
            $message = "Failed to create purchase request.";
            Session::put('main_error', $message );

            if(!Input::hasfile('file'))
            {
                Session::forget('imgerror');
            }
            // Get Other Error Messages
            $error_projectPurpose = $purchase->validationErrors->first('projectPurpose');
            $error_projectType = $purchase->validationErrors->first('projectType');
            $error_sourceOfFund = $purchase->validationErrors->first('sourceOfFund');
            $error_amount = $purchase->validationErrors->first('amount');
            $error_office = $purchase->validationErrors->first('office');
            $error_requisitioner = $purchase->validationErrors->first('requisitioner');
            $error_dateRequested = $purchase->validationErrors->first('dateRequested');
            $error_dateReceived = $purchase->validationErrors->first('dateReceived');
            $error_controlNo = $purchase->validationErrors->first('controlNo');

            // Inserting Error Message To a Session
            Session::put('error_projectPurpose', $error_projectPurpose );
            Session::put('error_sourceOfFund', $error_sourceOfFund );
            Session::put('error_amount', $error_amount );
            Session::put('error_office', $error_office );
            Session::put('error_requisitioner', $error_requisitioner );
            Session::put('error_dateRequested', $error_dateRequested );
            Session::put('error_dateReceived', $error_dateReceived );
            Session::put('error_projectType', $error_projectType );
            Session::put('error_controlNo', $error_controlNo );

            if($checkUniqueControlNo != 0)
            {
                Session::put('error_controlNo', 'This control no. already exists. Please enter a new one.' );
            }

            if(Input::get('hide_modeOfProcurement') == "")
            {
                Session::put('error_modeOfProcurement', 'required' );
            }

            if (Session::get('imgerror')&&Input::hasfile('file'))
            {
                $failedpurchasecount=Purchase::where('id', $purchase->id)->count();
                if($failedpurchasecount != 0)
                {
                    $failedpurchase=Purchase::find($purchase->id);
                    $failedpurchase->delete();
                }

                Session::forget('imgsuccess');
                //Image Error Return

                $task_details= TaskDetails::where('doc_id', $document->id)->delete();
                $document->delete();
                $message = "Failed to create purchase request.";

                // Set Main Error
                $message = "Failed to save purchase request.";
                Session::put('main_error', $message );

                // Get Other Error Messages
                $error_projectPurpose = $purchase->validationErrors->first('projectPurpose');
                $error_projectType = $purchase->validationErrors->first('projectType');
                $error_sourceOfFund = $purchase->validationErrors->first('sourceOfFund');
                $error_amount = $purchase->validationErrors->first('amount');
                $error_office = $purchase->validationErrors->first('office');
                $error_requisitioner = $purchase->validationErrors->first('requisitioner');
                $error_dateRequested = $purchase->validationErrors->first('dateRequested');
                $error_dateReceived = $purchase->validationErrors->first('dateReceived');
                $error_controlNo = $purchase->validationErrors->first('controlNo');

                // Inserting Error Message To a Session
                Session::put('error_projectPurpose', $error_projectPurpose );
                Session::put('error_sourceOfFund', $error_sourceOfFund );
                Session::put('error_amount', $error_amount );
                Session::put('error_office', $error_office );
                Session::put('error_requisitioner', $error_requisitioner );
                Session::put('error_dateRequested', $error_dateRequested );
                Session::put('error_dateReceived', $error_dateReceived );
                Session::put('error_projectType', $error_projectType );
                Session::put('error_controlNo', $error_controlNo );

                if($checkUniqueControlNo != 0)
                {
                    Session::put('error_controlNo', 'This control no. already exists. Please enter a new one.' );
                }
            }
            return Redirect::back()->withInput();
        }
    }

    public function autouploadsaved()
    {
        //Image Upload
        $purchasecheck=Purchase::orderby('id', 'DESC')->count();
        if($purchasecheck!=0)
        {
            $purchase=Purchase::orderby('id', 'DESC')->find(Input::get('id'));
            $docs=Document::orderby('id', 'DESC')->where('pr_id', $purchase->id)->first();
            $pr_id=$purchase->id;
            $doc_id=$docs->id;
        }
        else
        {
            $pr_id=1;
            $doc_id=1;
        }

        foreach(Input::file('file') as $file)
        {
            $rules = array(
                'file' => 'required|mimes:png,gif,jpeg,jpg|max:900000000000000000000'
            );

            $validator = \Validator::make(array('file'=> $file), $rules);
            $destine = public_path()."/uploads";
            if($validator->passes())
            {
                $ext = $file->guessClientExtension(); // (Based on mime type)
                $ext = $file->getClientOriginalExtension(); // (Based on filename)
                $filename = $file->getClientOriginalName();

                $archivo = value(function() use ($file)
                {
                    $filename = str_random(10) . '.' . $file->getClientOriginalExtension();
                    return strtolower($filename);
                });

                $archivo = value(function() use ($file)
                {
                    $date = date('m-d-Y-h-i-s', time());
                    $filename = $date."-". $file->getClientOriginalName();
                    return strtolower($filename);
                });

                $attach = new Attachments;
                $attach->doc_id=$doc_id;
                $attach->data = $archivo;
                $attach->saved = 1;
                $attach->save();

                $filename = $doc_id."_".$attach->id;
                $file->move($destine, $archivo);

                $target_folder = $destine;
                $upload_image = $target_folder."/".$archivo;

                $thumbnail = $target_folder."/resize".$archivo;
                $actual = $target_folder."/".$archivo;

                // THUMBNAIL SIZE
                list($width, $height) = getimagesize($upload_image);

                $newwidth = $width;
                $newheight = $height;

                while($newheight > 525)
                {
                    $newheight=$newheight*0.8;
                    $newwidth=$newwidth*0.8;
                }

                $source=$upload_image;
                $ext  = strtolower($ext);
                $thumb = imagecreatetruecolor($newwidth, $newheight);
                if ($ext=="jpg"||$ext=="jpeg")
                    $source = imagecreatefromjpeg($upload_image);
                elseif ($ext=="png")
                    $source = imagecreatefrompng($upload_image);
                elseif ($ext=="gif")
                    $source = imagecreatefromgif($upload_image);
                else
                    continue;

                // RESIZE
                imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

                // MAKE NEW FILES
                if ($ext=="jpg"||$ext=="jpeg")
                    imagejpeg($thumb, $thumbnail, 100);
                elseif ($ext=="png")
                    imagepng($thumb, $thumbnail, 9);
                elseif($ext=="gif")
                    imagegif($thumb, $thumbnail, 100);
                else
                    echo "An invalid image";

                unlink($actual);
                // FILE RENAMES
                rename($thumbnail, $actual);
            }
            else
            {
                Session::put('imgerror','Invalid file.');
            }
        }
        //End Image Upload
        return redirect::back()->withInput();
    }

    public function edit()
    {
        $user_id=Auth::user()->id;
        //Office restriction
        if (Entrust::hasRole('Administrator')){}
        else if(Entrust::hasRole('Procurement Personnel'))
        {
            $useroffice=Auth::user()->office_id;
            $maker= User::find( $purchase->requisitioner);
            $docget=Document::where('pr_id', $purchase->id)->first();
            $taskd = TaskDetails::where('doc_id',$docget->id)->where('assignee_id',$user_id)->count();

            if($taskd!=0)
            {}
            else if ($user_id==$purchase->created_by)
            {}
            else if ($useroffice!=$maker->office_id)
                return Redirect::to('dashboard');
        }
        else
        {
            $useroffice=Auth::user()->office_id;
            $maker= User::find( $purchase->requisitioner);
            if ($useroffice!=$maker->office_id)
                return Redirect::to('/dashboard');
        }

        //End Office restriction
        return View::make('pr_edit');
    }

    public function viewAll()
    {
        return View::make('purchaseRequest.purchaseRequest_all');
    }

    public function vieweach($id)
    {
        $purchase = Purchase::find($id);
        $wfName = DB::table('document')->where('pr_id',$id)->first();

        //Office restriction
        if (Entrust::hasRole('Administrator')){}
        else if(Entrust::hasRole('Procurement Personnel'))
        {

        }
        else
        {
            $useroffice=Auth::user()->office_id;
            $maker= User::find( $purchase->requisitioner);
            if ($useroffice!=$maker->office_id)
                return Redirect::to('/dashboard');
        }
        //End Office restriction

        // JAN SARMIENTO AWESOME CHECKLIST VARIABLES
        $secName1 = DB::table('section')->where('workflow_id',$wfName->work_id)->where('section_order_id','1')->first();
        $secName2 = DB::table('section')->where('workflow_id',$wfName->work_id)->where('section_order_id','2')->first();
        $secName3 = DB::table('section')->where('workflow_id',$wfName->work_id)->where('section_order_id','3')->first();

        if($wfName->work_id!=4)
        {
            $secName4 = DB::table('section')->where('workflow_id',$wfName->work_id)->where('section_order_id','4')->first();
            return View::make('purchaseRequest.purchaseRequest_view')
                ->with('purchase', $purchase)->with('wfName',$wfName)->with('secName1',$secName1)->with('secName2',$secName2)->with('secName3',$secName3)->with('secName4',$secName4);
        }
        else
        {
            return View::make('purchaseRequest.purchaseRequest_view')
                ->with('purchase', $purchase)->with('wfName',$wfName)->with('secName1',$secName1)->with('secName2',$secName2)->with('secName3',$secName3);
        }
    }

    public function viewSummary()
    {
        $prCount = 0;
        $POCount = 0;
        $chequeCount = 0;

        $reports = Reports::all();
        foreach ($reports as $report)
        {
            $prCount = $prCount + $report->pRequestCount;
            $POCount = $POCount + $report->pOrderCount;
            $chequeCount = $chequeCount + $report->chequeCount;
        }

        return View::make('purchaseRequest.summary')
            ->with('prCount',$prCount)
            ->with('POCount',$POCount)
            ->with('chequeCount',$chequeCount);
    }

    public function getDateRange()
    {
        $start = Input::get('start');
        $end = Input::get('end');

        return $start.' '.$end;
    }

    public function edit_submit()
    {
        $id = Input::get('id');
        $purchase = Purchase::find(Input::get('id'));
        $document = Document::where('pr_id', Input::get('id'))->first();
        $purchase->projectPurpose = strip_tags(Input::get( 'projectPurpose' ));
        $purchase->sourceOfFund = strip_tags(Input::get( 'sourceOfFund' ));
        $purchase->office = Input::get( 'office' );
        $purchase->requisitioner = Input::get( 'requisitioner' );
        $purchase->dateRequested = Input::get( 'dateRequested' );
        $purchase->dateReceived = Input::get( 'dateReceived' );
        $purchase->controlNo = Input::get('controlNo');
        $purchase_save = $purchase->save();

        if($purchase_save)
        {
            $document->pr_id = $purchase->id;
            $document_save = $document->save();

            if($document_save)
            {
                //Image Upload
                $doc_id= $document->id;

                foreach(Input::file('file') as $file)
                {
                    $rules = array('file' => 'required|mimes:png,gif,jpeg,jpg|max:90000000000000000000');
                    $validator = Validator::make(array('file'=> $file), $rules);
                    $destine=public_path()."/uploads";

                    if($validator->passes())
                    {
                        $ext = $file->guessClientExtension();
                        $ext = $file->getClientOriginalExtension();
                        $filename = $file->getClientOriginalName();

                        $archivo = value(function() use ($file)
                        {
                            $date = date('m-d-Y-h-i-s', time());
                            $filename = $date."-". $file->getClientOriginalName();
                            return strtolower($filename);
                        });

                        $attach = new Attachments;
                        $attach->doc_id=$doc_id;
                        $attach->data = $archivo;
                        $attach->save();

                        $filename= $doc_id."_".$attach->id;
                        $file->move($destine, $archivo);
                        $target_folder = $destine;
                        $upload_image = $target_folder."/".$archivo;
                        $thumbnail = $target_folder."/resize".$archivo;
                        $actual = $target_folder."/".$archivo;

                        // THUMBNAIL SIZE
                        list($width, $height) = getimagesize($upload_image);
                        $newwidth = $width;
                        $newheight = $height;

                        while ( $newheight> 525)
                        {
                            $newheight=$newheight*0.8;
                            $newwidth=$newwidth*0.8;
                        }

                        $thumb = imagecreatetruecolor($newwidth, $newheight);
                        $source=$upload_image;
                        $ext  = strtolower($ext);
                        if ($ext=="jpg"||$ext=="jpeg")
                            $source = imagecreatefromjpeg($upload_image);
                        elseif ($ext=="png")
                            $source = imagecreatefrompng($upload_image);
                        elseif ($ext=="gif")
                            $source = imagecreatefromgif($upload_image);
                        else
                            continue;

                        // RESIZE
                        imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

                        // MAKE NEW FILES
                        if ($ext=="jpg"||$ext=="jpeg")
                            imagejpeg($thumb, $thumbnail, 100);
                        elseif ($ext=="png")
                            imagepng($thumb, $thumbnail, 9);
                        elseif($ext="gif")
                            imagegif($thumb, $thumbnail, 100);
                        else
                            echo "Invalid image";

                        unlink($actual);
                        // FILE RENAMES
                        rename($thumbnail, $actual);
                    }
                    else
                    {
                        //Does not pass validation
                        $errors = $validator->errors();
                        Session::put('imgerror','Invalid file.');
                        break;
                    }
                }

                if (Session::get('imgerror')&&Input::hasfile('file'))
                {
                    //Image Error Return
                    // Set Main Error
                    $message = "Failed to save purchase request.";
                    Session::put('main_error', $message );


                    // Get Other Error Messages
                    $error_projectPurpose = $purchase->validationErrors->first('projectPurpose');
                    $error_projectType = $purchase->validationErrors->first('projectType');
                    $error_sourceOfFund = $purchase->validationErrors->first('sourceOfFund');
                    $error_office = $purchase->validationErrors->first('office');
                    $error_requisitioner = $purchase->validationErrors->first('requisitioner');
                    $error_dateRequested = $purchase->validationErrors->first('dateRequested');
                    $error_dateReceived = $purchase->validationErrors->first('dateReceived');

                    // Inserting Error Message To a Session
                    Session::put('error_projectPurpose', $error_projectPurpose );
                    Session::put('error_sourceOfFund', $error_sourceOfFund );
                    Session::put('error_office', $error_office );
                    Session::put('error_requisitioner', $error_requisitioner );
                    Session::put('error_dateRequested', $error_dateRequested );
                    Session::put('error_dateReceived', $error_dateReceived );
                    Session::put('error_projectType', $error_projectType );

                    return Redirect::back()->withInput();
                }

                Session::put('imgsuccess','Files uploaded.');

                if (Session::get('imgerror'))
                    Session::forget('imgerror');

                //End Image Upload
                $pr_id= Session::get('pr_id');

                DB::table('attachments')->where('doc_id', $doc_id)->update(array( 'saved' => 1));
                DB::table('attachments')->where('saved', '=', 0)->delete();
                Session::forget('doc_id');

                $notice = "Purchase request saved successfully. ";
                Session::put('notice', $notice);

                return Redirect::to("purchaseRequest/vieweach/$id");
            }
        }
        else
        {
            // Set Main Error
            $message = "Failed to save purchase request.";
            Session::put('main_error', $message );

            // Get Other Error Messages
            $error_projectPurpose = $purchase->validationErrors->first('projectPurpose');
            $error_sourceOfFund = $purchase->validationErrors->first('sourceOfFund');
            $error_office = $purchase->validationErrors->first('office');
            $error_requisitioner = $purchase->validationErrors->first('requisitioner');
            $error_dateRequested = $purchase->validationErrors->first('dateRequested');
            $error_dateReceived = $purchase->validationErrors->first('dateReceived');
            // Inserting Error Message To a Session
            Session::put('error_projectPurpose', $error_projectPurpose );
            Session::put('error_sourceOfFund', $error_sourceOfFund );
            Session::put('error_office', $error_office );
            Session::put('error_requisitioner', $error_requisitioner );
            Session::put('error_dateRequested', $error_dateRequested );
            Session::put('error_dateReceived', $error_dateReceived );

            // Get Other Error Messages
            $error_projectPurpose = $purchase->validationErrors->first('projectPurpose');
            $error_projectType = $purchase->validationErrors->first('projectType');
            $error_sourceOfFund = $purchase->validationErrors->first('sourceOfFund');
            $error_office = $purchase->validationErrors->first('office');
            $error_requisitioner = $purchase->validationErrors->first('requisitioner');
            $error_dateRequested = $purchase->validationErrors->first('dateRequested');
            $error_dateReceived = $purchase->validationErrors->first('dateReceived');

            // Inserting Error Message To a Session
            Session::put('error_projectPurpose', $error_projectPurpose );
            Session::put('error_sourceOfFund', $error_sourceOfFund );
            Session::put('error_office', $error_office );
            Session::put('error_requisitioner', $error_requisitioner );
            Session::put('error_dateRequested', $error_dateRequested );
            Session::put('error_dateReceived', $error_dateReceived );
            Session::put('error_projectType', $error_projectType );

            return Redirect::back()->withInput();
        }
    }

    public function changeForm($id)
    {
        $reason = Input::get('hide_reason');
        $purchase = Purchase::find($id);
        $purchase->status = "Cancelled";
        $purchase->reason = $reason;
        $purchase->save();
        $doc= Document::where('pr_id',$id)->first();
        TaskDetails::where('doc_id', '=', $doc->id)->where('status','!=','Done')->delete();
        DB::table('count')->where('doc_id',$doc->id)->delete();
        $users= User::get();

        foreach ($users as $user)
        {
            $count =new Count;
            $count->user_id=$user->id;
            $count->doc_id=$doc->id;
            $count->save();
        }

        Session::put('notice', 'Purchase request has been cancelled.' );
        return Redirect::to("purchaseRequest/view");
    }

    public function checklistedit()
    {
        Session::put('goToChecklist', 'true' );

        //Initializations
        $taskdetails_id= Input::get('taskdetails_id');

        $assignee=" ".strip_tags(Input::get('assignee'));
         $assignee = preg_replace('/\s+/', ' ',$assignee);
         if($assignee==" ")
            $assignee="None";

        $mydate=Input::get('dateFinished');
        $timestamp = strtotime($mydate);
        $dateFinished= date("Y-m-d H:i:s", $timestamp);
        $daysOfAction=Input::get('daysOfAction');
        $remarks=" ".strip_tags(Input::get('remarks'));
        $remarks = preg_replace('/\s+/', ' ',$remarks);
        $check=0;
         $taskd= TaskDetails::find($taskdetails_id);
            $docs=Document::find($taskd->doc_id);
            if ($taskd->status=="Done")
            {
                Session::put('errorchecklist', 'Saved failed. Task was already completed by another user.');
                return Redirect::back();
            }
        //Validation Process

        if(ctype_alnum(str_replace(array(' ', '-', '.', ',', 'ñ', 'Ñ',"'"),'',$remarks)))
            $check=$check+1;

        if(ctype_alpha(str_replace(array(' ', '-', '.', ',', 'ñ', 'Ñ', "'"),'',$assignee)))
            $check=$check+1;

        if(ctype_digit($daysOfAction))
        {
            if($daysOfAction>=0)
                $check=$check+1;
        }

        if (($check==3||($remarks==" "&&$check==2))&&$assignee!=NULL)
        {


            $id=$docs->pr_id;
            //PO Section Check
            $taskcurrent=Task::find($taskd->task_id);
            if($taskcurrent->taskName=="BAC (DELIVERY)"||$taskcurrent->taskName=="Governor's Office")
            {
                $dateFinished = substr($dateFinished, 0, strrpos($dateFinished, ' '));

                $reports = Reports::whereDate($dateFinished)->first();

                if($reports == null)
                {
                    $reports = new Reports;
                    $reports->date = $dateFinished;
                    $reports->pOrderCount = 1;
                    //return $reports . " aw " . $dateFinished;
                }
                else
                {
                    $reports->pOrderCount = $reports->pOrderCount + 1;
                }

                $reports->save();
            }

            //End PO Section Check
            $delcount= Count::where('doc_id', $docs->id)->delete();
            $userx= User::get();
            foreach($userx as $userv)
            {
                $count= new Count;
                $count->user_id= $userv->id;
                $count->doc_id= $docs->id;
                $count->save();
            }

            Session::put('successchecklist','Task completed.');

            $taskd= TaskDetails::find($taskdetails_id);
            $taskd->status="Done";
            $taskd->daysOfAction=$daysOfAction;
            $taskd->dateFinished=$dateFinished;
            $taskd->assignee=$assignee;
            $remarks= ltrim ($remarks,'0');
            $taskd->remarks=$remarks;
            $taskd->save();
            $tasknext=TaskDetails::find($taskdetails_id+1);
            $tasknextc=TaskDetails::where('id', $taskdetails_id+1)->where('doc_id', $docs->pr_id)->count();
            date_default_timezone_set("Asia/Manila");
            $upDate = date('Y-m-d H:i:s');
            DB::table('purchase_request')->where('id',$id)->update(array('updated_at' => $upDate));

            if ($tasknextc!=0)
            {
                //Project Type Filter
                $counter=1;
                $tasknext=TaskDetails::find($taskdetails_id+$counter);

                while($tasknext->status=="Lock"||$tasknext->status=="Done")
                {
                    $counter=$counter+1;
                    $tasknext=TaskDetails::find($taskdetails_id+$counter);
                }

                $tasknext->status="New";
                $tasknext->save();
                //End Project Type Filter/
            }
            else
            {
                $purchase= Purchase::find($docs->pr_id);
                $purchase->status="Closed";
                $purchase->save();
                $request_id = Input::get('pr_id');
                return Redirect::to("purchaseRequest/vieweach/$request_id");
            }
        }
        else
        {
            Session::put('errorchecklist','Invalid input.');
            return Redirect::back()->withInput();
        }

        return Redirect::back();
    }

    public function insertaddon()
    {
        //Initialization
        Session::put('goToChecklist', 'true' );
        $otherDetails_id= Input::get('otherDetails_id');
        $purchase_request_id= Input::get('purchase_request_id');
        $value= strip_tags(Input::get('value'));

        if(ctype_alnum(str_replace(str_split(' !\\/:*?".,|'),'',$value)))
        {
            $insertvalue= new Values;
            $insertvalue->otherDetails_id=$otherDetails_id;
            $insertvalue->purchase_request_id=$purchase_request_id;
            $insertvalue->value=$value;
            $insertvalue->save();
            Session::forget('retainOtherDetails');
            Session::forget('retainId');
            Session::put('successlabel', 'Successfully saved.');
            return Redirect::back();
        }
        else
        {
            Session::put('retainOtherDetails', $value);
            Session::put('retainId', $otherDetails_id);
            Session::put('errorlabel','Invalid input.');
            return Redirect::back();
        }
    }

    public function editaddon()
    {
        Session::put('goToChecklist', 'true' );

        $values_id= Input::get('values_id');
        $insertvalue= Values::find($values_id);
        Session::put('retainOtherDetails', $insertvalue->value);
        Session::put('retainId', $insertvalue->otherDetails_id);
        $insertvalue->delete();
        return Redirect::back();
    }

    public function editpagecall($id)
    {
        return View::make('pr_edit')->with('id',$id);
    }

    public function delimage()
    {
        $id = Input::get('hide');
        $attachn=DB::table('attachments')->where('id', $id)->first();
        $destine= public_path()."/uploads/";
        unlink($destine.$attachn->data);
        $attach = DB::table('attachments')->where('id', $id)->delete();
        $notice="Attachment successfully deleted.";
        return Redirect::back()->with('notice', $notice);
    }

    //Other Tasks Functions
    public function certification()
    {
        Session::put('goToChecklist', 'true' );
        //Initializations
        $radio=Input::get('radio');


        $by=" ".strip_tags(Input::get('by'));
         $by= preg_replace('/\s+/', ' ',$by);
         if($by==" ")
            $by="None";

        $taskdetails_id=Input::get('taskdetails_id');
        $check=0;

        //Validation Process
        if(ctype_alpha(str_replace(array(' ', '-', '.', ',', 'ñ', 'Ñ'),'',$by)))
            $check=$check+1;

        if ($check==1)
        {
            $taskd= TaskDetails::find($taskdetails_id);
            $docs=Document::find($taskd->doc_id);
            if($taskd->status=="Done")
            {
                Session::put('errorchecklist', 'Saved failed. Task was already completed by another user.');
                return Redirect::back();
            }
            $id=$docs->pr_id;
            $delcount= Count::where('doc_id', $docs->id)->delete();
            $userx= User::get();
            foreach($userx as $userv)
            {
                $count= new Count;
                $count->user_id= $userv->id;
                $count->doc_id= $docs->id;
                $count->save();
            }

            Session::put('successchecklist','Task completed.');

            $taskd= TaskDetails::find($taskdetails_id);
            $taskd->status="Done";
            $taskd->custom1=$radio;
            $taskd->custom2=$by;
            $taskd->save();
            $tasknext=TaskDetails::find($taskdetails_id+1);
            $tasknextc=TaskDetails::where('id', $taskdetails_id+1)->where('doc_id', $docs->pr_id)->count();
            date_default_timezone_set("Asia/Manila");
            $upDate = date('Y-m-d H:i:s');
            DB::table('purchase_request')->where('id',$id)->update(array('updated_at' => $upDate));
            DB::table('taskdetails')->where('id', $taskdetails_id)->update(array('dateFinished' => $upDate));
            if ($tasknextc!=0)
            {
                //Project Type Filter
                $counter=1;
                $tasknext=TaskDetails::find($taskdetails_id+$counter);

                while($tasknext->status=="Lock"||$tasknext->status=="Done")
                {
                    $counter=$counter+1;
                    $tasknext=TaskDetails::find($taskdetails_id+$counter);
                }

                $tasknext->status="New";
                $tasknext->save();
                //End Project Type Filter
            }
            else
            {
                $purchase= Purchase::find($docs->pr_id);
                $purchase->status="Closed";
                $purchase->save();
                $request_id = Input::get('pr_id');
                return Redirect::to("purchaseRequest/vieweach/$request_id");
            }
        }
        else
        {
            Session::put('errorchecklist','Invalid input.');
            return Redirect::back()->withInput();
        }
        return Redirect::back();
    }

    public function posting()
    {
        Session::put('goToChecklist', 'true' );
        //Initializations
        $date=Input::get('date');
        $referenceno=strip_tags(Input::get('referenceno'));


        $by=" ".strip_tags(Input::get('by'));
         $by= preg_replace('/\s+/', ' ',$by);
         if($by==" ")
            $by="None";

        $taskdetails_id=Input::get('taskdetails_id');
        $check=0;

        //Validation Process

        if(ctype_alpha(str_replace(array(' ', '-', '.', ',', 'ñ', 'Ñ'),'',$by)))
            $check=$check+1;
        if(ctype_alnum(str_replace(array(' ', '-', '.'),'',$referenceno)))
            $check=$check+1;
        if (trim(Input::get('date'))=="01/01/70")
            $check=0;

        if ($check==2)
        {
            $taskd= TaskDetails::find($taskdetails_id);
            $docs=Document::find($taskd->doc_id);
            if($taskd->status=="Done")
            {
                Session::put('errorchecklist', 'Saved failed. Task was already completed by another user.');
                return Redirect::back();
            }
            $id=$docs->pr_id;
            $delcount= Count::where('doc_id', $docs->id)->delete();
            $userx= User::get();
            foreach($userx as $userv)
            {
                $count= new Count;
                $count->user_id= $userv->id;
                $count->doc_id= $docs->id;
                $count->save();
            }

            Session::put('successchecklist','Task completed.');

            $taskd= TaskDetails::find($taskdetails_id);
            $taskd->status="Done";
            $taskd->custom1=$referenceno;
            $taskd->custom2=$date;

            $taskd->custom3=$by;
            $taskd->save();
            $tasknext=TaskDetails::find($taskdetails_id+1);
            $tasknextc=TaskDetails::where('id', $taskdetails_id+1)->where('doc_id', $docs->pr_id)->count();
            date_default_timezone_set("Asia/Manila");
            $upDate = date('Y-m-d H:i:s');
            DB::table('purchase_request')->where('id',$id)->update(array('updated_at' => $upDate));
            DB::table('taskdetails')->where('id', $taskdetails_id)->update(array('dateFinished' => $date));
            if ($tasknextc!=0)
            {
                //Project Type Filter
                $counter=1;
                $tasknext=TaskDetails::find($taskdetails_id+$counter);

                while($tasknext->status=="Lock"||$tasknext->status=="Done")
                {
                    $counter=$counter+1;
                    $tasknext=TaskDetails::find($taskdetails_id+$counter);
                }

                $tasknext->status="New";
                $tasknext->save();
                //End Project Type Filter
            }
            else
            {
                $purchase= Purchase::find($docs->pr_id);
                $purchase->status="Closed";
                $purchase->save();
                $request_id = Input::get('pr_id');
                return Redirect::to("purchaseRequest/vieweach/$request_id");
            }
        }
        else
        {
            Session::put('errorchecklist','Invalid input.');
            return Redirect::back()->withInput();
        }

        return Redirect::back();
    }

    public function supplier()
    {
        Session::put('goToChecklist', 'true' );
        //Initializations
        $supplier=Input::get('supplier');
        $amount=Input::get('amount');
        $taskdetails_id=Input::get('taskdetails_id');
        $check=0;
        $taskd= TaskDetails::find($taskdetails_id);
            $docs=Document::find($taskd->doc_id);
            if ($taskd->status=="Done")
            {
                Session::put('errorchecklist', 'Saved failed. Task was already completed by another user.');
                return Redirect::back();
            }
        //Validation Process
        if(ctype_alnum(str_replace(array(' ', '-', '.',"'"),'',$supplier)))
            $check=$check+1;
        if(ctype_digit(str_replace(array(' ', ',', '.'),'',$amount)))
            $check=$check+1;

        if ($check==2)
        {

            $id=$docs->pr_id;
            $delcount= Count::where('doc_id', $docs->id)->delete();
            $userx= User::get();
            foreach($userx as $userv)
            {
                $count= new Count;
                $count->user_id= $userv->id;
                $count->doc_id= $docs->id;
                $count->save();
            }

            Session::put('successchecklist','Task completed.');

            $taskd= TaskDetails::find($taskdetails_id);
            $taskd->status="Done";
            $taskd->custom1=$supplier;
            $taskd->custom2=$amount;
            $taskd->save();
            $tasknext=TaskDetails::find($taskdetails_id+1);
            $tasknextc=TaskDetails::where('id', $taskdetails_id+1)->where('doc_id', $docs->pr_id)->count();
            date_default_timezone_set("Asia/Manila");
            $upDate = date('Y-m-d H:i:s');
            DB::table('purchase_request')->where('id',$id)->update(array('updated_at' => $upDate));
            DB::table('taskdetails')->where('id', $taskdetails_id)->update(array('dateFinished' => $upDate));

            if ($tasknextc!=0)
            {
                //Project Type Filter
                $counter=1;
                $tasknext=TaskDetails::find($taskdetails_id+$counter);

                while($tasknext->status=="Lock"||$tasknext->status=="Done")
                {
                    $counter=$counter+1;
                    $tasknext=TaskDetails::find($taskdetails_id+$counter);
                }

                $tasknext->status="New";
                $tasknext->save();
                //End Project Type Filter
            }
            else
            {
                $purchase= Purchase::find($docs->pr_id);
                $purchase->status="Closed";
                $purchase->save();
                $request_id = Input::get('pr_id');
                return Redirect::to("purchaseRequest/vieweach/$request_id");
            }
        }
        else
        {
            Session::put('errorchecklist','Invalid input.');
            return Redirect::back()->withInput();
        }

        return Redirect::back();
    }

    public function cheque()
    {
        Session::put('goToChecklist', 'true' );
        //Initializations
        $amt=Input::get('amt');
        $num=Input::get('num');
        $date=Input::get('date');
        $taskdetails_id=Input::get('taskdetails_id');
        $check=0;

        //Validation Process
        if(ctype_alnum(str_replace(array(' ', '-', '.'),'',$num)))
            $check=$check+1;
        if(ctype_digit(str_replace(array(' ', ',', '.'),'',$amt)))
            $check=$check+1;
        if (trim(Input::get('date'))=="01/01/70")
            $check=0;

        if ($check==2)
        {
            $taskd= TaskDetails::find($taskdetails_id);
            $docs=Document::find($taskd->doc_id);
            if($taskd->status=="Done")
            {
                Session::put('errorchecklist', 'Saved failed. Task was already completed by another user.');
                return Redirect::back();
            }
            $id=$docs->pr_id;
            //Cheque

            $timestamp = strtotime($date);
            $dateFinished = date("Y-m-d H:i:s", $timestamp);

            $dateFinished = substr($dateFinished, 0, strrpos($dateFinished, ' '));

            $reports = Reports::whereDate($dateFinished)->first();

            if($reports == null)
            {
                $reports = new Reports;
                $reports->date = $dateFinished;
                $reports->chequeCount = 1;
            }
            else
            {
                $reports->chequeCount = $reports->chequeCount + 1;
            }
            $reports->save();

            //End Cheque
            $delcount= Count::where('doc_id', $docs->id)->delete();
            $userx= User::get();
            foreach($userx as $userv)
            {
                $count= new Count;
                $count->user_id= $userv->id;
                $count->doc_id= $docs->id;
                $count->save();
            }

            Session::put('successchecklist','Task completed.');

            $taskd= TaskDetails::find($taskdetails_id);
            $taskd->status="Done";
            $taskd->custom1=$amt;
            $taskd->custom2=$num;
            $taskd->custom3=$date;

            $taskd->save();
            $tasknext=TaskDetails::find($taskdetails_id+1);
            $tasknextc=TaskDetails::where('id', $taskdetails_id+1)->where('doc_id', $docs->pr_id)->count();
            date_default_timezone_set("Asia/Manila");
            $upDate = date('Y-m-d H:i:s');
            DB::table('purchase_request')->where('id',$id)->update(array('updated_at' => $upDate));
            DB::table('taskdetails')->where('id', $taskdetails_id)->update(array('dateFinished' => $upDate));
            if ($tasknextc!=0)
            {
                //Project Type Filter
                $counter=1;
                $tasknext=TaskDetails::find($taskdetails_id+$counter);

                while($tasknext->status=="Lock"||$tasknext->status=="Done")
                {
                    $counter=$counter+1;
                    $tasknext=TaskDetails::find($taskdetails_id+$counter);
                }

                $tasknext->status="New";
                $tasknext->save();
                //End Project Type Filter
            }
            else
            {
                $purchase= Purchase::find($docs->pr_id);
                $purchase->status="Closed";
                $purchase->save();
                $request_id = Input::get('pr_id');
                return Redirect::to("purchaseRequest/vieweach/$request_id");
            }
        }
        else
        {
            Session::put('errorchecklist','Invalid input.');
            return Redirect::back()->withInput();
        }

        return Redirect::back();
    }

    public function published()
    {
        Session::put('goToChecklist', 'true' );
        //Initializations
        $datepublished=Input::get('datepublished');
        $enddate=Input::get('enddate');


        $by=" ".strip_tags(Input::get('by'));
         $by= preg_replace('/\s+/', ' ',$by);
         if($by==" ")
            $by="None";

        $taskdetails_id=Input::get('taskdetails_id');
        $check=0;

        //Validation Process
        if(ctype_alnum(str_replace(array(' ', '-', '.', ',', 'ñ', 'Ñ'),'',$by)))
            $check=$check+1;

        if($check==1)
        {
            $taskd= TaskDetails::find($taskdetails_id);
            $docs=Document::find($taskd->doc_id);
            if($taskd->status=="Done")
            {
                Session::put('errorchecklist', 'Saved failed. Task was already completed by another user.');
                return Redirect::back();
            }
            $id=$docs->pr_id;
            $delcount= Count::where('doc_id', $docs->id)->delete();
            $userx= User::get();
            foreach($userx as $userv)
            {
                $count= new Count;
                $count->user_id= $userv->id;
                $count->doc_id= $docs->id;
                $count->save();
            }

            Session::put('successchecklist','Task completed.');

            $taskd= TaskDetails::find($taskdetails_id);
            $taskd->status="Done";
            $taskd->custom1=$datepublished;
            $timestamp = strtotime($datepublished);
            $dateFinished= date("Y-m-d H:i:s", $timestamp);
            $taskd->dateFinished=$dateFinished;
            $taskd->custom2=$enddate;

            $taskd->custom3=$by;

            $taskd->save();
            $tasknext=TaskDetails::find($taskdetails_id+1);
            $tasknextc=TaskDetails::where('id', $taskdetails_id+1)->where('doc_id', $docs->pr_id)->count();
            date_default_timezone_set("Asia/Manila");
            $upDate = date('Y-m-d H:i:s');
            DB::table('purchase_request')->where('id',$id)->update(array('updated_at' => $upDate));

            if ($tasknextc!=0)
            {
                //Project Type Filter
                $counter=1;
                $tasknext=TaskDetails::find($taskdetails_id+$counter);

                while($tasknext->status=="Lock"||$tasknext->status=="Done")
                {
                    $counter=$counter+1;
                    $tasknext=TaskDetails::find($taskdetails_id+$counter);
                }

                $tasknext->status="New";
                $tasknext->save();
                //End Project Type Filter
            }
            else
            {
                $purchase= Purchase::find($docs->pr_id);
                $purchase->status="Closed";
                $purchase->save();
                $request_id = Input::get('pr_id');
                return Redirect::to("purchaseRequest/vieweach/$request_id");
            }
        }
        else
        {
            Session::put('errorchecklist','Invalid input.');
            return Redirect::back()->withInput();
        }

        return Redirect::back();
    }

    public function documents()
    {
        Session::put('goToChecklist', 'true' );
        //Initializations
        $date=Input::get('date');
        $biddingdate=Input::get('biddingdate');


        $by=" ".strip_tags(Input::get('by'));
         $by= preg_replace('/\s+/', ' ',$by);
         if($by==" ")
            $by="None";
        $taskdetails_id=Input::get('taskdetails_id');
        $check=0;
        $taskd= TaskDetails::find($taskdetails_id);
            $docs=Document::find($taskd->doc_id);
            if ($taskd->status=="Done")
            {
                Session::put('errorchecklist', 'Saved failed. Task was already completed by another user.');
                return Redirect::back();
            }

        //Validation Process
        if(ctype_alnum(str_replace(array(' ', '-', '.', ',', 'ñ', 'Ñ'),'',$by)))
            $check=$check+1;
        if(trim(Input::get('date'))=="01/01/70")
            $check=0;

        if ($check==1)
        {

            $id=$docs->pr_id;
            $delcount= Count::where('doc_id', $docs->id)->delete();
            $userx= User::get();
            foreach($userx as $userv)
            {
                $count= new Count;
                $count->user_id= $userv->id;
                $count->doc_id= $docs->id;
                $count->save();
            }

            Session::put('successchecklist','Task completed.');

            $taskd= TaskDetails::find($taskdetails_id);
            $taskd->status="Done";
            $taskd->custom1=$date;

            $taskd->custom2=$biddingdate;
            $taskd->custom3=$by;

            $taskd->save();
            $tasknext=TaskDetails::find($taskdetails_id+1);
            $tasknextc=TaskDetails::where('id', $taskdetails_id+1)->where('doc_id', $docs->pr_id)->count();
            date_default_timezone_set("Asia/Manila");
            $upDate = date('Y-m-d H:i:s');
            DB::table('purchase_request')->where('id',$id)->update(array('updated_at' => $upDate));
            DB::table('taskdetails')->where('id', $taskdetails_id)->update(array('dateFinished' => $date));

            if ($tasknextc!=0)
            {
                //Project Type Filter
                $counter=1;
                $tasknext=TaskDetails::find($taskdetails_id+$counter);

                while($tasknext->status=="Lock"||$tasknext->status=="Done")
                {
                    $counter=$counter+1;
                    $tasknext=TaskDetails::find($taskdetails_id+$counter);
                }

                $tasknext->status="New";
                $tasknext->save();
                //End Project Type Filter
            }
            else
            {
                $purchase= Purchase::find($docs->pr_id);
                $purchase->status="Closed";
                $purchase->save();
                $request_id = Input::get('pr_id');
                return Redirect::to("purchaseRequest/vieweach/$request_id");
            }
        }
        else
        {
            Session::put('errorchecklist','Invalid input.');
            return Redirect::back()->withInput();
        }

        return Redirect::back();
    }

    public function evaluations()
    {
        Session::put('goToChecklist', 'true' );
        //Initializations
        $date=Input::get('date');
        $noofdays=Input::get('noofdays');
        $taskdetails_id=Input::get('taskdetails_id');
        $check=0;

        //Validation Process
        if(ctype_digit($noofdays))
            $check=$check+1;
        if(trim(Input::get('date'))=="01/01/70")
            $check=0;

        if ($check==1)
        {
            $taskd= TaskDetails::find($taskdetails_id);
            $docs=Document::find($taskd->doc_id);
            if($taskd->status=="Done")
            {
                Session::put('errorchecklist', 'Saved failed. Task was already completed by another user.');
                return Redirect::back();
            }
            $id=$docs->pr_id;
            $delcount= Count::where('doc_id', $docs->id)->delete();
            $userx= User::get();
            foreach($userx as $userv)
            {
                $count= new Count;
                $count->user_id= $userv->id;
                $count->doc_id= $docs->id;
                $count->save();
            }

            Session::put('successchecklist','Task completed.');

            $taskd= TaskDetails::find($taskdetails_id);
            $taskd->status="Done";
            $taskd->custom1=$date;
            $taskd->custom2=$noofdays;

            $timestamp = strtotime($date);
            $dtime=date("Y-m-d H:i:s", $timestamp);

            $taskd->dateFinished=$dtime;

            $taskd->save();
            $tasknext=TaskDetails::find($taskdetails_id+1);
            $tasknextc=TaskDetails::where('id', $taskdetails_id+1)->where('doc_id', $docs->pr_id)->count();
            date_default_timezone_set("Asia/Manila");
            $upDate = date('Y-m-d H:i:s');
            DB::table('purchase_request')->where('id',$id)->update(array('updated_at' => $upDate));
            DB::table('taskdetails')->where('id', $taskdetails_id)->update(array('dateFinished' => $date));
            if ($tasknextc!=0)
            {
                //Project Type Filter
                $counter=1;
                $tasknext=TaskDetails::find($taskdetails_id+$counter);

                while($tasknext->status=="Lock"||$tasknext->status=="Done")
                {
                    $counter=$counter+1;
                    $tasknext=TaskDetails::find($taskdetails_id+$counter);
                }

                $tasknext->status="New";
                $tasknext->save();
                //End Project Type Filter
            }
            else
            {
                $purchase= Purchase::find($docs->pr_id);
                $purchase->status="Closed";
                $purchase->save();
                $request_id = Input::get('pr_id');
                return Redirect::to("purchaseRequest/vieweach/$request_id");
            }
        }
        else
        {
            Session::put('errorchecklist','Invalid input.');
            return Redirect::back()->withInput();
        }

        return Redirect::back();
    }

    public function conference()
    {
        Session::put('goToChecklist', 'true' );
        //Initializations

        $date=Input::get('date');
        $taskdetails_id=Input::get('taskdetails_id');
        $check=0;

            $taskd= TaskDetails::find($taskdetails_id);
            $docs=Document::find($taskd->doc_id);
            if ($taskd->status=="Done")
            {
                Session::put('errorchecklist', 'Saved failed. Task was already completed by another user.');
                return Redirect::back();
            }
        if (trim(Input::get('date'))=="01/01/70")
            $check=-1;
        //Validation Process

        if ($check==0)
        {
            $id=$docs->pr_id;
            $delcount= Count::where('doc_id', $docs->id)->delete();
            $userx= User::get();
            foreach($userx as $userv)
            {
                $count= new Count;
                $count->user_id= $userv->id;
                $count->doc_id= $docs->id;
                $count->save();
            }

            Session::put('successchecklist','Task completed.');

            $taskd= TaskDetails::find($taskdetails_id);
            $taskd->status="Done";
            $taskd->custom1=$date;
            $taskd->save();
            $tasknext=TaskDetails::find($taskdetails_id+1);
            $tasknextc=TaskDetails::where('id', $taskdetails_id+1)->where('doc_id', $docs->pr_id)->count();
            date_default_timezone_set("Asia/Manila");
            $upDate = date('Y-m-d H:i:s');
            DB::table('purchase_request')->where('id',$id)->update(array('updated_at' => $upDate));
             $date= date('Y-m-d H:i:s', strtotime($date));
            DB::table('taskdetails')->where('id', $taskdetails_id)->update(array('dateFinished' => $date));
            if ($tasknextc!=0)
            {
                //Project Type Filter
                $counter=1;
                $tasknext=TaskDetails::find($taskdetails_id+$counter);

                while($tasknext->status=="Lock"||$tasknext->status=="Done")
                {
                    $counter=$counter+1;
                    $tasknext=TaskDetails::find($taskdetails_id+$counter);
                }

                $tasknext->status="New";
                $tasknext->save();
                //End Project Type Filter
            }
            else
            {
                $purchase= Purchase::find($docs->pr_id);
                $purchase->status="Closed";
                $purchase->save();
                $request_id = Input::get('pr_id');
                return Redirect::to("purchaseRequest/vieweach/$request_id");
            }
        }
        else
        {
            Session::put('errorchecklist','Invalid input.');
            return Redirect::back()->withInput();
        }

        return Redirect::back();
    }
    //End Other Tasks Functions

    public function contractmeeting()
    {
        Session::put('goToChecklist', 'true' );
        //Initializations
        $date=Input::get('date');
        $noofdays=Input::get('noofdays');
        $contractmeeting=Input::get('contractmeeting');

        $taskdetails_id=Input::get('taskdetails_id');
        $check=0;
         $taskd= TaskDetails::find($taskdetails_id);
            $docs=Document::find($taskd->doc_id);
            if ($taskd->status=="Done")
            {
                Session::put('errorchecklist', 'Saved failed. Task was already completed by another user.');
                return Redirect::back();
            }
        //Validation Process
        if(ctype_alnum(str_replace(array(' ', '-', '.'),'',$contractmeeting)))
            $check=$check+1;
        if(ctype_digit($noofdays))
            $check=$check+1;
        if (trim(Input::get('date'))=="01/01/70")
            $check=0;
        if ($check==2)
        {

            $id=$docs->pr_id;
            $delcount= Count::where('doc_id', $docs->id)->delete();
            $userx= User::get();
            foreach($userx as $userv)
            {
                $count= new Count;
                $count->user_id= $userv->id;
                $count->doc_id= $docs->id;
                $count->save();
            }

            Session::put('successchecklist','Task completed.');

            $taskd= TaskDetails::find($taskdetails_id);
            $taskd->status="Done";
            $taskd->custom1=$date;
            $taskd->custom2=$noofdays;
            $taskd->custom3=$contractmeeting;
            $timestamp = strtotime($date);
            $dtime=date("Y-m-d H:i:s", $timestamp);

            $taskd->dateFinished=$dtime;
            $taskd->save();
            $tasknext=TaskDetails::find($taskdetails_id+1);
            $tasknextc=TaskDetails::where('id', $taskdetails_id+1)->where('doc_id', $docs->pr_id)->count();
            date_default_timezone_set("Asia/Manila");
            $upDate = date('Y-m-d H:i:s');
            DB::table('purchase_request')->where('id',$id)->update(array('updated_at' => $upDate));

            if ($tasknextc!=0)
            {
                //Project Type Filter
                $counter=1;
                $tasknext=TaskDetails::find($taskdetails_id+$counter);

                while($tasknext->status=="Lock"||$tasknext->status=="Done")
                {
                    $counter=$counter+1;
                    $tasknext=TaskDetails::find($taskdetails_id+$counter);
                }

                $tasknext->status="New";
                $tasknext->save();
                //End Project Type Filter
            }
            else
            {
                $purchase= Purchase::find($docs->pr_id);
                $purchase->status="Closed";
                $purchase->save();
                $request_id = Input::get('pr_id');
                return Redirect::to("purchaseRequest/vieweach/$request_id");
            }
        }
        else
        {
            Session::put('errorchecklist','Invalid input.');
            return Redirect::back()->withInput();
        }

        return Redirect::back();
    }

    public function rfq()
    {
        Session::put('goToChecklist', 'true' );
        //Initializations
        $noofsuppliers=Input::get('noofsuppliers');
        $date=Input::get('date');


        $by=" ".strip_tags(Input::get('by'));
         $by= preg_replace('/\s+/', ' ',$by);
         if($by==" ")
            $by="None";

        $taskdetails_id=Input::get('taskdetails_id');
        $check=0;

        //Validation Process
        if(ctype_alnum(str_replace(array(' ', '-', '.', ',', 'ñ', 'Ñ'),'',$by)))
            $check=$check+1;
        if(ctype_digit($noofsuppliers))
            $check=$check+1;
        if(trim(Input::get('date'))=="01/01/70")
            $check=0;

        if ($check==2)
        {
            $taskd= TaskDetails::find($taskdetails_id);
            $docs=Document::find($taskd->doc_id);
            if($taskd->status=="Done")
            {
                Session::put('errorchecklist', 'Saved failed. Task was already completed by another user.');
                return Redirect::back();
            }
            $id=$docs->pr_id;
            $delcount= Count::where('doc_id', $docs->id)->delete();
            $userx= User::get();
            foreach($userx as $userv)
            {
                $count= new Count;
                $count->user_id= $userv->id;
                $count->doc_id= $docs->id;
                $count->save();
            }

            Session::put('successchecklist','Task completed.');

            $taskd= TaskDetails::find($taskdetails_id);
            $taskd->status="Done";
            $taskd->custom1=$noofsuppliers;
            $taskd->custom2=$date;
            $taskd->custom3=$by;
            $taskd->save();
            $tasknext=TaskDetails::find($taskdetails_id+1);
            $tasknextc=TaskDetails::where('id', $taskdetails_id+1)->where('doc_id', $docs->pr_id)->count();
            date_default_timezone_set("Asia/Manila");
            $upDate = date('Y-m-d H:i:s');
            DB::table('purchase_request')->where('id',$id)->update(array('updated_at' => $upDate));
            DB::table('taskdetails')->where('id', $taskdetails_id)->update(array('dateFinished' => $date));
            if ($tasknextc!=0)
            {
                //Project Type Filter
                $counter=1;
                $tasknext=TaskDetails::find($taskdetails_id+$counter);

                while($tasknext->status=="Lock"||$tasknext->status=="Done")
                {
                    $counter=$counter+1;
                    $tasknext=TaskDetails::find($taskdetails_id+$counter);
                }

                $tasknext->status="New";
                $tasknext->save();
                //End Project Type Filter
            }
            else
            {
                $purchase= Purchase::find($docs->pr_id);
                $purchase->status="Closed";
                $purchase->save();
                $request_id = Input::get('pr_id');
                return Redirect::to("purchaseRequest/vieweach/$request_id");
            }
        }
        else
        {
            Session::put('errorchecklist','Invalid input.');
            return Redirect::back()->withInput();
        }

        return Redirect::back();
    }

    public function datebyremark()
    {
        Session::put('goToChecklist', 'true' );
        //Initializations
        $taskdetails_id= Input::get('taskdetails_id');
        $assignee=" ".strip_tags(Input::get('assignee'));
         $assignee = preg_replace('/\s+/', ' ',$assignee);
         if($assignee==" ")
            $assignee="None";
        $mydate=Input::get('dateFinished');
        $timestamp = strtotime($mydate);
        $dateFinished= date("Y-m-d H:i:s", $timestamp);

        $remarks=" ".strip_tags(Input::get('remarks'));
        $remarks = preg_replace('/\s+/', ' ',$remarks);
        $check=0;
         $taskd= TaskDetails::find($taskdetails_id);
            $docs=Document::find($taskd->doc_id);
            if ($taskd->status=="Done")
            {
                Session::put('errorchecklist', 'Saved failed. Task was already completed by another user.');
                return Redirect::back();
            }

        //Validation Process

        if(ctype_alnum(str_replace(array(' ', '-', '.', ',', 'ñ', 'Ñ', "'"),'',$remarks)))
            $check=$check+1;

        if(ctype_alpha(str_replace(array(' ', '-', '.', ',', 'ñ', 'Ñ', "'"),'',$assignee)))
            $check=$check+1;

        if (($check==2||($remarks==" "&&$check==1))&&$assignee!=NULL)
        {

            $id=$docs->pr_id;
            $delcount= Count::where('doc_id', $docs->id)->delete();
            $userx= User::get();
            foreach($userx as $userv)
            {
                $count= new Count;
                $count->user_id= $userv->id;
                $count->doc_id= $docs->id;
                $count->save();
            }

            Session::put('successchecklist','Task completed.');

            $taskd= TaskDetails::find($taskdetails_id);
            $taskd->status="Done";

            $taskd->dateFinished=$dateFinished;
            $taskd->assignee=$assignee;
            $remarks= ltrim ($remarks,'0');
            $taskd->remarks=$remarks;
            $taskd->save();
            $tasknext=TaskDetails::find($taskdetails_id+1);
            $tasknextc=TaskDetails::where('id', $taskdetails_id+1)->where('doc_id', $docs->pr_id)->count();
            date_default_timezone_set("Asia/Manila");
            $upDate = date('Y-m-d H:i:s');
            DB::table('purchase_request')->where('id',$id)->update(array('updated_at' => $upDate));
            if ($tasknextc!=0)
            {
                //Project Type Filter
                $counter=1;
                $tasknext=TaskDetails::find($taskdetails_id+$counter);

                while($tasknext->status=="Lock"||$tasknext->status=="Done")
                {
                    $counter=$counter+1;
                    $tasknext=TaskDetails::find($taskdetails_id+$counter);
                }

                $tasknext->status="New";
                $tasknext->save();
                //End Project Type Filter
            }
            else
            {
                $purchase= Purchase::find($docs->pr_id);
                $purchase->status="Closed";
                $purchase->save();
                $request_id = Input::get('pr_id');
                return Redirect::to("purchaseRequest/vieweach/$request_id");
            }
        }
        else
        {
            Session::put('errorchecklist','Invalid input.');
            return Redirect::back()->withInput();
        }

        return Redirect::back();
    }

    public function dateby()
    {
        Session::put('goToChecklist', 'true' );
        //Initializations
        $taskdetails_id= Input::get('taskdetails_id');
        $assignee=" ".strip_tags(Input::get('assignee'));
         $assignee = preg_replace('/\s+/', ' ',$assignee);
         if($assignee==" ")
            $assignee="None";
        $mydate=Input::get('dateFinished');
        $timestamp = strtotime($mydate);
        $dateFinished= date("Y-m-d H:i:s", $timestamp);
        $check=0;
         $taskd= TaskDetails::find($taskdetails_id);
            $docs=Document::find($taskd->doc_id);
            if($taskd->status=="Done")
            {
                Session::put('errorchecklist', 'Saved failed. Task was already completed by another user.');
                return Redirect::back();
            }
        //Validation Process
        if(ctype_alpha(str_replace(array(' ', '-', '.', ',', 'ñ', 'Ñ'),'',$assignee)))
            $check=$check+1;
        if (($check==1)&&$assignee!=NULL)
        {

            $id=$docs->pr_id;
            $delcount= Count::where('doc_id', $docs->id)->delete();
            $userx= User::get();
            foreach($userx as $userv)
            {
                $count= new Count;
                $count->user_id= $userv->id;
                $count->doc_id= $docs->id;
                $count->save();
            }

            Session::put('successchecklist','Task completed.');

            $taskd= TaskDetails::find($taskdetails_id);
            $taskd->status="Done";
            $taskd->dateFinished=$dateFinished;
            $taskd->assignee=$assignee;
            $taskd->save();
            $tasknext=TaskDetails::find($taskdetails_id+1);
            $tasknextc=TaskDetails::where('id', $taskdetails_id+1)->where('doc_id', $docs->pr_id)->count();
            date_default_timezone_set("Asia/Manila");
            $upDate = date('Y-m-d H:i:s');
            DB::table('purchase_request')->where('id',$id)->update(array('updated_at' => $upDate));
            if ($tasknextc!=0)
            {
                //Project Type Filter
                $counter=1;
                $tasknext=TaskDetails::find($taskdetails_id+$counter);

                while($tasknext->status=="Lock"||$tasknext->status=="Done")
                {
                    $counter=$counter+1;
                    $tasknext=TaskDetails::find($taskdetails_id+$counter);
                }

                $tasknext->status="New";
                $tasknext->save();

                //End Project Type Filter
            }
            else
            {
                $purchase= Purchase::find($docs->pr_id);
                $purchase->status="Closed";
                $purchase->save();
                $request_id = Input::get('pr_id');
                return Redirect::to("purchaseRequest/vieweach/$request_id");
            }
        }
        else
        {
            Session::put('errorchecklist','Invalid input.');
            return Redirect::back()->withInput();
        }

        return Redirect::back();
    }

    public function dateonly()
    {
        Session::put('goToChecklist', 'true' );
        //Initializations
        $date=Input::get('dateFinished');

        $taskdetails_id=Input::get('taskdetails_id');
        $check=0;
        $taskd= TaskDetails::find($taskdetails_id);
            $docs=Document::find($taskd->doc_id);
            if($taskd->status=="Done")
            {
                Session::put('errorchecklist', 'Saved failed. Task was already completed by another user.');
                return Redirect::back();
            }
        //Validation Process
        $check=1;
        if (trim(Input::get('date'))=="01/01/70")
            $check=0;
        if ($check==1)
        {

            $id=$docs->pr_id;
            $delcount= Count::where('doc_id', $docs->id)->delete();
            $userx= User::get();
            foreach($userx as $userv)
            {
                $count= new Count;
                $count->user_id= $userv->id;
                $count->doc_id= $docs->id;
                $count->save();
            }

            Session::put('successchecklist','Task completed.');

            $taskd= TaskDetails::find($taskdetails_id);
            $taskd->status="Done";
            $taskd->custom1=$date;

            $timestamp = strtotime($date);
            $dateFinished= date("Y-m-d H:i:s", $timestamp);
            $taskd->dateFinished=$dateFinished;
            $taskd->save();
            $tasknext=TaskDetails::find($taskdetails_id+1);
            $tasknextc=TaskDetails::where('id', $taskdetails_id+1)->where('doc_id', $docs->pr_id)->count();
            date_default_timezone_set("Asia/Manila");
            $upDate = date('Y-m-d H:i:s');
            DB::table('purchase_request')->where('id',$id)->update(array('updated_at' => $upDate));

            if ($tasknextc!=0)
            {
                //Project Type Filter
                $counter=1;
                $tasknext=TaskDetails::find($taskdetails_id+$counter);

                while($tasknext->status=="Lock"||$tasknext->status=="Done")
                {
                    $counter=$counter+1;
                    $tasknext=TaskDetails::find($taskdetails_id+$counter);
                }

                $tasknext->status="New";
                $tasknext->save();
                //End Project Type Filter
            }
            else
            {
                $purchase= Purchase::find($docs->pr_id);
                $purchase->status="Closed";
                $purchase->save();
                $request_id = Input::get('pr_id');
                return Redirect::to("purchaseRequest/vieweach/$request_id");
            }
        }
        else
        {
            Session::put('errorchecklist','Invalid input.');
            return Redirect::back()->withInput();
        }

        return Redirect::back();
    }

    public function taskedit($id)
    {
        $taskd= TaskDetails::find($id);
        $taskeditcount=TaskDetails::where('doc_id', $taskd->doc_id)->where('status','Edit')->count();
        if ($taskeditcount==0)
        {
            $taskd->status="Edit";
            $taskd->save();
        }
        else
        {
            Session::put('errorchecklist', 'A task is currently being edited by another user.');
        }

        Session::put('goToChecklist', 'true' );
        return Redirect::back();
    }

    public function taskcanceledit($id)
    {
        $taskd= TaskDetails::find($id);
        $taskd->status="Done";
        $taskd->save();

        Session::put('goToChecklist', 'true' );
        return Redirect::back();
    }

    public function philgeps()
    {
        Session::put('goToChecklist', 'true' );
        //Initializations
        $referenceno=strip_tags(Input::get('referenceno'));
        $datepublished=Input::get('datepublished');
        $enddate=Input::get('enddate');


        $by=" ".strip_tags(Input::get('by'));
         $by= preg_replace('/\s+/', ' ',$by);
         if($by==" ")
            $by="None";

        $taskdetails_id=Input::get('taskdetails_id');
        $check=0;

            $taskd= TaskDetails::find($taskdetails_id);
            $docs=Document::find($taskd->doc_id);
            if($taskd->status=="Done")
            {
                Session::put('errorchecklist', 'Saved failed. Task was already completed by another user.');
                return Redirect::back();
            }

        //Validation Process
        if(ctype_alnum(str_replace(array(' ', '-', '.', ',', 'ñ', 'Ñ'),'',$by)))
            $check=$check+1;
        if(ctype_alnum(str_replace(array(' ', '-', '.'),'',$referenceno)))
            $check=$check+1;

        if ($check==2)
        {
            $id=$docs->pr_id;
            $delcount= Count::where('doc_id', $docs->id)->delete();
            $userx= User::get();
            foreach($userx as $userv)
            {
                $count= new Count;
                $count->user_id= $userv->id;
                $count->doc_id= $docs->id;
                $count->save();
            }

            Session::put('successchecklist','Task completed.');

            $taskd= TaskDetails::find($taskdetails_id);
            $taskd->status="Done";
            $taskd->custom1=$referenceno;
            $taskd->custom2=$datepublished;
            $taskd->custom3=$enddate;
            $taskd->assignee=$by;

            $taskd->save();
            $tasknext=TaskDetails::find($taskdetails_id+1);
            $tasknextc=TaskDetails::where('id', $taskdetails_id+1)->where('doc_id', $docs->pr_id)->count();
            date_default_timezone_set("Asia/Manila");
            $upDate = date('Y-m-d H:i:s');
            DB::table('purchase_request')->where('id',$id)->update(array('updated_at' => $upDate));
            DB::table('taskdetails')->where('id', $taskdetails_id)->update(array('dateFinished' => $upDate));
            if ($tasknextc!=0)
            {
                //Project Type Filter
                $counter=1;
                $tasknext=TaskDetails::find($taskdetails_id+$counter);

                while($tasknext->status=="Lock"||$tasknext->status=="Done")
                {
                    $counter=$counter+1;
                    $tasknext=TaskDetails::find($taskdetails_id+$counter);
                }

                $tasknext->status="New";
                $tasknext->save();
                //End Project Type Filter
            }
            else
            {
                $purchase= Purchase::find($docs->pr_id);
                $purchase->status="Closed";
                $purchase->save();
                $request_id = Input::get('pr_id');
                return Redirect::to("purchaseRequest/vieweach/$request_id");
            }
        }
        else
        {
            Session::put('errorchecklist','Invalid input.');
            return Redirect::back()->withInput();
        }

        return Redirect::back();
    }


    public function cancelcreate()
    {
  Attachments::where('saved', '0')->delete();
   return Redirect::to("purchaseRequest/view");
       }
}
