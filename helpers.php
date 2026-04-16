<? php 

/**
*load new
*@param string $NAME
*@return void 
*/

function loadView($name) 
{$viewPath = basePath ("Views/{$name}.view.php");
if(file_exists($viewPath)){
    require $viewPath;
}else{
    echo "partials {$name}not found";
}
}
function loadPartial($name) {
    $partialPath = basePath ("Views/partials/{name}.php");
if(file_exists($partialPath)){
    require $partialPath;
}else{
    echo "partials {$name}not found";
}
}

?>