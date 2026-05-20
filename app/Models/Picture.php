<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class Picture extends Model
{
    protected $table = 'pictures';

    protected $fillable = [
        'name',
        'content',
        'path',
        'disk',
        'mime',
    ];

    public function formatContent($path)
    {
        return sprintf('0x%s', bin2hex(file_get_contents($path)));
    }

    public function getContent()
    {
        if ($this->path && $this->disk && Storage::disk($this->disk)->exists($this->path)) {
            return Storage::disk($this->disk)->get($this->path);
        }

        return hex2bin(substr(stream_get_contents($this->content), 2));
    }

    public function ext()
    {
        return pathinfo($this->name, PATHINFO_EXTENSION);
    }

    public static function upload($file)
    {
        $name = $file->getClientOriginalName();

        $picture = new Picture;
        $picture->name = $name;
        $picture->content = $picture->formatContent($file->getRealPath());
        $picture->mime = $file->getClientMimeType();

        $disk = self::resolveDisk();
        $path = sprintf('pictures/%s_%s', uniqid('', true), $name);
        Storage::disk($disk)->put($path, file_get_contents($file->getRealPath()));
        $picture->disk = $disk;
        $picture->path = $path;
        $picture->save();

        return $picture;
    }

    public static function fromUrl(string $url, ?string $name = null): ?Picture
    {
        $response = Http::timeout(10)->get($url);
        if (! $response->ok()) {
            return null;
        }

        $body = $response->body();
        if ($body === '') {
            return null;
        }

        $resolvedName = $name ?: basename(parse_url($url, PHP_URL_PATH) ?: 'image');
        if (! str_contains($resolvedName, '.')) {
            $resolvedName .= '.jpg';
        }

        $mime = $response->header('Content-Type') ?: 'image/jpeg';

        $picture = new Picture;
        $picture->name = $resolvedName;
        $picture->content = sprintf('0x%s', bin2hex($body));
        $picture->mime = $mime;

        $disk = self::resolveDisk();
        $path = sprintf('pictures/%s_%s', uniqid('', true), $resolvedName);
        Storage::disk($disk)->put($path, $body);
        $picture->disk = $disk;
        $picture->path = $path;
        $picture->save();

        return $picture;
    }

    private static function resolveDisk(): string
    {
        $hasS3 = env('AWS_ACCESS_KEY_ID')
            && env('AWS_SECRET_ACCESS_KEY')
            && env('AWS_BUCKET');

        return $hasS3 ? 's3' : 'uploads';
    }
}
