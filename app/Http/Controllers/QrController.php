<?php

namespace App\Http\Controllers;

use App\Qr;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Libraries\Fpdf\Fpdf;

class QrController extends Controller
{
    private $model = Qr::class;
    private $controller = self::class;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('qr.index', [
            'items' => $this->model::all(),
            'controller' => $this->controller,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('qr.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //возврат к списку Qr-кодов если нажата кнопка отмены
        if($request->submit == 'cancel'){
            return redirect(action([self::class, 'index']));
        //выполнение логики сохранения новой записи Qr-кода если нажата кнопка сохранить
        } else if($request->submit == 'save'){
            
            //валидация полей формы 
            $request->validate([
            'name' => 'string|required|max:100',
            'city' => 'string|required|max:100',
            'campaign' => 'string|required|max:100',
            'source' => 'url|required',
            'product' => 'string|required|max:100',
            ]);
            
            //сохрание новой модели Qr-кода в БД
            $qr_code = new $this->model;
            $qr_code->name = $request->name;
            $qr_code->save();
            
            $new_row = $this->model::find($qr_code->id);
            $new_row->options = json_encode([
                'City' => $request->city, 
                'Campaign' => $request->campaign, 
                //формирование ссылки для механизма промежуточного шлюза
                'Source' => url('/') . '/qr-codes/gateway?id=' . $new_row->id . '&&url=' . $request->source, 
                'Product' => $request->product, 
            ]);
            $new_row->save();
            
            return redirect(action([self::class, 'index']));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Qr  $qr
     * @return \Illuminate\Http\Response
     */
    public function show(Qr $qr)
    {
        //
    }


    public function edit(int $qr_row_id)
    {
        $qr_row = $this->model::find($qr_row_id);
        $options = json_decode($qr_row->options);
        
        return view('qr.edit', [
            'qr_row_id' => $qr_row->id,
            'qr_row_name' => $qr_row->name,
            'options' => $options,
        ]);
    }


    public function update(Request $request, int $qr_row_id)
    {
        //возврат к списку Qr-кодов если нажата кнопка отмены
        if($request->submit == 'cancel'){
            return redirect(action([self::class, 'index']));
        //выполнение логики обновления записи Qr-кода если нажата кнопка обновить    
        } else if($request->submit == 'update'){
           
            //валидация полей формы 
            $request->validate([
            'name' => 'string|required|max:100',
            'city' => 'string|required|max:100',
            'campaign' => 'string|required|max:100',
            'source' => 'url|required',
            'product' => 'string|required|max:100',
            ]);
            //обновление модели Qr-кода
            $qr_row = $this->model::find($qr_row_id);
            $qr_row->name = $request->name;
            $qr_row->options = json_encode([
                'City' => $request->city, 
                'Campaign' => $request->campaign, 
                //формирование ссылки для механизма промежуточного шлюза
                'Source' => url('/') . '/qr-codes/gateway?id=' . $qr_row->id . '&&url=' . $request->source, 
                'Product' => $request->product, 
            ]);
            $qr_row->save();
            
            return redirect(action([self::class, 'index']));
        }
    }

 
    public function destroy(int $qr_row_id)
    {
        $row = $this->model::findOrFail($qr_row_id);
        $row->delete();
        
        return redirect(action([self::class, 'index']));
    }
    
    public function getQr(int $qr_row_id)
    {
        //определение переменной,содержащей информацию для QR-кода
        $qr_content = '';
        
        //формирование имён файлов
        $file_name_part = 'QR-' . time();
        $png_file_name = $file_name_part . '.png';
        $pdf_file_name = $file_name_part . '.pdf';
        $jpeg_file_name = $file_name_part . '.jpeg';
        
        //определение путей для сохранения файлов
        $path = 'img/qr-code/';
        $png_file_path = public_path($path . $png_file_name);
        $pdf_file_path = public_path($path . $pdf_file_name);
        $jpeg_file_path = public_path($path . $jpeg_file_name);
        
        //получаем объект по id из запроса
        $qr_row = $this->model::find($qr_row_id);
        
        //наполнение переменной,содержащей информацию для QR-кода
        $qr_content .= 'ID:' . $qr_row->id . ';';
        $qr_content .= 'QR-name:' . $qr_row->name . ';';
        $qr_content .= implode('; ', array_map(
                            function ($v, $k) { return sprintf("%s:%s", $k, $v); },
                            $input = json_decode($qr_row->options,true),
                            array_keys($input)
                        ));
        $qr_content .= 'Created at:' . $qr_row->created_at . ';';
        
        //получение QR-кода в формате .png и запись в хранилище
        QrCode::encoding('UTF-8')
                ->format('png')
                ->size(200)->errorCorrection('H')
                ->generate($qr_content,$png_file_path);
         
        //формирование .pdf с QR-кодом при помощи класса FPDF
        $pdf = new Fpdf( 'P', 'mm', 'A4' );
        $pdf->AddPage();
        $pdf->Image($png_file_path, 20, 20, 100, 100 ,'');
        $pdf->Output('F', $pdf_file_path );
        
        //формирование .jpeg с QR-кодом 
        $image = imagecreatefrompng($png_file_path);
        $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
        imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
        imagealphablending($bg, TRUE);
        imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
        imagedestroy($image);
        $quality = 100; // 0 = worst / smaller file, 100 = better / bigger file 
        imagejpeg($bg, $jpeg_file_path, $quality);
        imagedestroy($bg);
        
        //вывод QR-кода на страницу с сылками для скачивания
        return view('qr.download', [
              'controller' => $this->controller,
              'qr_name' => $qr_row->name,
              'qr_content' => $qr_content,
              'files' => [
                  'png' => $png_file_name,
                  'pdf' => $pdf_file_name,
                  'jpeg' => $jpeg_file_name,
              ],
        ]);
    }
    
    public function downloadQr(string $file_name) 
    {        
        $path = 'img/qr-code/';
        $file_path = public_path($path . $file_name);
        
        return response()->download($file_path);
    }
    
    public function gateway(Request $request) 
    {
        //получение id и целевого url из параметра запроса
        $qr_id = $request->id;
        $redirect_url = $request->url;
        
        //получение объекта по id
        $qr_row = $this->model::find($qr_id);
        
        //обновление счётчика переходов
        $qr_row->transition_count += 1;
        $qr_row->save();
        
        return view('qr.gateway', [
              'redirect_url' => $redirect_url,
        ]);
    }
}
