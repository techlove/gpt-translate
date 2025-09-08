<?php

namespace Techlove\GptTranslate;

use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;

class OpenaiService
{
    public function translate_file($path = '.', $origin = 'en', $lang = 'sv', $context = '', $model = 'gpt-4o')
    {
        // get file from original content
        $file_origin = $path."/$origin.json";
        // decode json file into array
        $strings = json_decode(file_get_contents($file_origin), true);
        // translate each string
        $translated_strings = [];
        foreach (array_chunk($strings, 20) as $stringsPart) {
            $result = $this->translate_string($stringsPart, $origin, $lang, $context, $model);
            foreach ($result as $key => $value) {
                $translated_strings[$stringsPart[$key]] = $value;
            }
        }
        // encode translated strings into json
        $json = json_encode($translated_strings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        // define file path
        $file = $path."/$lang.json";
        // if file path does not exist, create it
        if (! file_exists($file)) {
            // verify if directory exists
            if (! file_exists(dirname($file))) {
                // if directory does not exist, create it
                mkdir(dirname($file), 0777, true);
            } else {
                // if directory exists, create file
                touch($file);
            }
        } else {
            // if file exists only add new strings that are not in the file
            $old_strings = json_decode(file_get_contents($file), true);
            $new_strings = array_diff($translated_strings, $old_strings);
            $translated_strings = array_merge($old_strings, $new_strings);
            $json = json_encode($translated_strings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        // save file
        return file_put_contents($file, $json);
    }

    public function translate_string($strings = [], $origin = 'en', $lang = 'sv', $context = '', $model = 'gpt-4o')
    {
        try {
            $result = OpenAI::chat()->create([
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $this->prompt_system($context)],
                    ['role' => 'user', 'content' => $this->prompt_header($origin, $lang)],
                    ['role' => 'user', 'content' => collect($strings)->implode("\n")],
                ],
                'temperature' => 0.4,
                'n' => 1,
            ]);
            // if the result is not empty, return the translated string
            if ($result->choices && count($result->choices) > 0 && $result->choices[0]->message) {
                Log::debug('t', [$result->choices[0]->message->content]);
                $translations = $result->choices[0]->message->content ?? $strings;

                return explode("\n", $translations);
            } else {
                return $strings;
            }
        } catch (\Throwable $th) {
            Log::debug('t', [$th->getMessage()]);

            return $strings;
        }
    }

    public function prompt_system($context = '')
    {
        if ($context != '') {
            return "You are a translator. Your job is to translate the following text into the specified language, using the given context: $context.";
        } else {
            return 'You are a translator. Your job is to translate the following text to the language specified in the prompt.';
        }
    }

    public function prompt_header($origin = 'en', $lang = 'sv')
    {
        $str_origin = 'english';
        switch ($origin) {
            case 'en':
                $str_origin = 'english';
                break;
            case 'sv':
                $str_origin = 'swedish';
                break;
            case 'da':
                $str_origin = 'danish';
                break;
            case 'no':
                $str_origin = 'norwegian';
                break;
            case 'fi':
                $str_origin = 'finnish';
                break;
            case 'nl':
                $str_origin = 'dutch';
                break;
            case 'pl':
                $str_origin = 'polish';
                break;
            case 'es':
                $str_origin = 'spanish';
                break;
            case 'fr':
                $str_origin = 'french';
                break;
            case 'de':
                $str_origin = 'german';
                break;
            case 'it':
                $str_origin = 'italian';
                break;
            case 'pt':
                $str_origin = 'portuguese';
                break;
            default:
                $str_origin = 'english';
                break;
        }
        $str_lang = 'english';
        switch ($lang) {
            case 'en':
                $str_lang = 'english';
                break;
            case 'sv':
                $str_lang = 'swedish';
                break;
            case 'da':
                $str_lang = 'danish';
                break;
            case 'no':
                $str_lang = 'norwegian';
                break;
            case 'fi':
                $str_lang = 'finnish';
                break;
            case 'nl':
                $str_lang = 'dutch';
                break;
            case 'pl':
                $str_lang = 'polish';
                break;
            case 'es':
                $str_lang = 'spanish';
                break;
            case 'fr':
                $str_lang = 'french';
                break;
            case 'de':
                $str_lang = 'german';
                break;
            case 'it':
                $str_lang = 'italian';
                break;
            case 'pt':
                $str_lang = 'portuguese';
                break;
            default:
                $str_lang = 'english';
                break;
        }

        $description = "Translate the following text from $str_origin to $str_lang, ensuring you return only the translated content without added quotes or any other extraneous details.";
        $rule = "The translation strings contain variables matching PCRE2 regex /[([]*:(\w+)[)\]]*/xg - Variable names should be kept in $str_origin.";

        return "$description $rule";
    }

    public function sync_vars($str1, $str2)
    {

        // find all variables with subfix :
        preg_match_all('/[([]*:(\w+)[)\]]*/', $str1, $matches);
        if ($matches && isset($matches[0])) {
            // for each variable with subfix : found in str1, replace it with the same variable in str2
            foreach ($matches[0] as $match) {
                $str2 = preg_replace('/'.$match.'/', $match, $str2, 1);
            }
        }

        // return new string with replaced variables
        return $str2;
    }
}
