<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory;

    // Relation to Termsmeta
    public function meta()
    {
        return $this->hasOne(Termmeta::class, 'term_id')->where('key', 'content');
    }

    // Relation To Termsmeta
    public function paymentMeta()
    {
        return $this->hasOne(Termmeta::class, 'term_id')->where('key', 'content');
    }

    // Relation To TermsMeta
    public function excerpt()
    {
        return $this->hasOne(Termmeta::class, 'term_id')->where('key', 'excerpt');
    }

    // Relation To TermsMeta
    public function thum_image()
    {
        return $this->hasOne(Termmeta::class, 'term_id')->where('key', 'thum_image');
    }

    // Relation To TermsMeta
    public function description()
    {
        return $this->hasOne(Termmeta::class, 'term_id')->where('key', 'description');
    }

    // Relation To TermsMeta
    public function page()
    {
        return $this->hasOne(Termmeta::class, 'term_id')->where('key', 'page');
    }

    // Relation To TermsMeta
    public function investor()
    {
        return $this->hasOne(Termmeta::class, 'term_id')->where('key', 'investor');
    }
    
    //Currency to Termsmeta
    public function currencyMeta()
    {
        return $this->hasOne(Termmeta::class, 'term_id')->where('key', 'content');
    }

    //How it works to Termsmeta
    public function howitworksMeta()
    {
        return $this->hasOne(Termmeta::class, 'term_id')->where('key', 'howitworks');
    }

    //Services to Termsmeta
    public function serviceMeta()
    {
        return $this->hasOne(Termmeta::class, 'term_id')->where('key', 'services');
    }

    // Relation To TermsMeta
    public function feedback()
    {
        return $this->hasOne(Termmeta::class, 'term_id')->where('key', 'feedback');
    }

    public function term()
    {
        return $this->hasMany(Term::class, 'term_id')->where('key', 'currency');
    }

    

}
