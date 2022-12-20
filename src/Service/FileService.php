<?php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class FileService{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    //delete some files in public/files/
    public function deleteAllFiles(){
        $dir = $this->params->get("upload_dir");
        $all = scandir($dir);
        foreach($all as $item){
            if(is_file($dir.$item)){
                if(!str_contains($item, 'fixtures') && !str_contains($item, 'favicon'))unlink($dir.$item);
            }
        }
    }

    public function deleteOneFile(string $path, string $filename=""){
        if($filename != ''){
            if(str_contains($filename, 'fixtures') || str_contains($filename, 'favicon'))return;
        }
        unlink($path);
    }
}