<?

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class WbApi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'wbapi';
    }
}
