<?php
namespace App\Transformers;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
*  Class Json is transformers from raw data to json view
*/
class Json
{
	public static function response($data = null, $message = null, $code = 200, $additional=null, $action = null)
    {	
        if ($message==null) {
            $message = __('message.success');
        }
        if ($data==null) {
            $data = [];
        }
        $result = [];
    	
        if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $dt = $data->toArray();
            // dd($dt);
            $result['meta']['count'] = $dt['to'];
            $result['meta']['total'] = $dt['total'];
            $result['links']['first']=$dt['first_page_url'];
            $result['links']['last']=$dt['last_page_url'];
            $result['links']['next']=$dt['next_page_url'];
            $result['links']['prev']=$dt['prev_page_url'];
    		$result['data'] = $dt['data'];
            $result['data']['links']['self'] = app('url')->full() ;
    	} else {
    		$result['data'] = $data;
            $result['meta'] = [
                'status' => true,
                'code' => $code
            ];
    	}

        if ($additional!=null) {
            $result['included'] = $additional;
        }

	    return response()->json($result, $code);
    }

    public static function exception($message = null, $error = null, $code=400)
    {	
        if ($message==null) {
            $message = __('message.error');
        }

	    $meta['message'] = $message;
	    $meta['status'] = false;
        // dd();
        if ($error instanceof NotFoundHttpException) {    
            $meta['error']['message'] = $error->getMessage();
            $meta['error']['file'] = $error->getFile();
            $meta['error']['line'] = $error->getLine();
        } elseif ($error instanceof \Exception) {    

            $meta['error']['message'] = $error->getMessage();
            $meta['error']['file'] = $error->getFile();
            $meta['error']['line'] = $error->getLine();
        } elseif(is_array($error) && count($error) > 0) {
    	   $meta['error'] = $error; 
        }
	    return response()->json(compact('meta'), $code);
    }
    
}

