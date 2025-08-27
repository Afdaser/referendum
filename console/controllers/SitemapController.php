<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use common\models\Language;
use common\models\Tag;
use common\models\Poll;

class SitemapController extends Controller
{
    public static $subdomains= [];
    protected static $data = [];
    protected static $types = ['poll', 'tags'];
    protected static $protocol = 'https://';
    protected static $domen = SITE_DOMAIN;
    protected static $br = "\n";
    protected static $minimalDate = '2021-10-20';
    protected static $dir = '';

    public function actionIndex()
    {
        self::$dir = __DIR__ . '/../../';

        self::setSubdomains();

        $sitemap['tags'] = self::generateTag();
        $sitemap['poll'] = self::generatePoll();
        $sitemap['indexes'] = self::generateIndexes();

        foreach (static::$subdomains as $langId => $subdomain) {
            if ($subdomain) {
                foreach (self::$types as $key) {
                    $filename = "sitemaps/{$subdomain}.{$key}.xml";
                    file_put_contents(self::$dir . $filename, self::$data[$key]['xml'][$langId]);
                }
                $filename = "sitemaps/{$subdomain}.index.xml";
                file_put_contents(self::$dir . $filename, self::$data['indexes']['xml'][$langId]);

                self::$data['index']['links'] = [
                    self::$data['index']['dates'][$langId],
                ];
            }
        }
        self::generateIndex();
        echo "Generated successfully\n";
    }

    protected static function setSubdomains()
    {
        $langs = Language::find()->orderBy(['id' => SORT_ASC])->all();
        static::$subdomains[0] = '';
        foreach ($langs as $lang) {
            static::$subdomains[$lang->id] = $lang->name;
        }
    }

    public function generateIndex()
    {
        self::$data['index']['xml'] = '<?xml version="1.0" encoding="UTF-8"?>';
        self::$data['index']['xml'] .= self::$br . '<?xml-stylesheet type="text/xsl" href="//'.self::$domen.'/main-sitemap.xsl"?>';
        self::$data['index']['xml'] .= self::$br . '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach (static::$subdomains as $langId => $subdomain) {
            if ($subdomain) {
                self::$data['index']['xml'] .= self::$br . '<sitemap>';
                self::$data['index']['xml'] .= self::$br . '<loc>'.self::$protocol."{$subdomain}.".self::$domen."/sitemap.xml".'</loc>';
                self::$data['index']['xml'] .= self::$br . '<lastmod>'.self::$data['index']['dates'][$langId].'</lastmod>';
                self::$data['index']['xml'] .= self::$br . '</sitemap>';
            }
        }
        self::$data['index']['xml'] .= self::$br . '</sitemapindex>';
        $filename = "sitemaps/index.xml";
        file_put_contents(self::$dir . $filename, self::$data['index']['xml']);
    }

    protected static function generateIndexes()
    {
        foreach (static::$subdomains as $langId => $subdomain) {
            if($langId){
                foreach (self::$types as $key) {
                    self::$data['indexes']['links'][$langId][] = [
                        'url' =>  self::$protocol."{$subdomain}.".self::$domen."/{$key}-sitemap.xml",
                        'lastmod' => !empty(self::$data[$key]['maxDates'][$langId]) ? self::$data[$key]['maxDates'][$langId] : self::$data[$key]['maxDate'],
                    ];
                }
            }
        }
        self::$data['index']['dates'] = array_fill_keys(array_keys(static::$subdomains), self::$minimalDate);
        self::$data['indexes']['xml'] = [];
        foreach (static::$subdomains as $langId => $subdomain) {
            if($langId){
                self::$data['indexes']['xml'][$langId] = '<?xml version="1.0" encoding="UTF-8"?>';
                self::$data['indexes']['xml'][$langId] .= self::$br . '<?xml-stylesheet type="text/xsl" href="//'.$subdomain.'.'.self::$domen.'/main-sitemap.xsl"?>';
                self::$data['indexes']['xml'][$langId] .= self::$br . '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
                foreach (self::$data['indexes']['links'][$langId] as $item) {
                    self::$data['indexes']['xml'][$langId] .= self::$br . '<sitemap>';
                    self::$data['indexes']['xml'][$langId] .= self::$br . "<loc>{$item['url']}</loc>";
                    self::$data['indexes']['xml'][$langId] .= self::$br . "<lastmod>{$item['lastmod']}</lastmod>";
                    self::$data['indexes']['xml'][$langId] .= self::$br . '</sitemap>';
                    if(self::$data['index']['dates'][$langId] < $item['lastmod']){
                        self::$data['index']['dates'][$langId] = $item['lastmod'];
                    }
                }
                self::$data['indexes']['xml'][$langId] .= self::$br . '</sitemapindex>';
            }
        }
    }

    protected static function generatePoll()
    {
        $select = [
            'if(p.date_add is null, "0", p.date_add)',
            'if(p.date_update is null, "0", p.date_update)',
            'if(pc.date_add is null, "0", pc.date_add)',
            'if(prv.date_add is null, "0", prv.date_add)',
        ];

        $list = Poll::find()
            ->select(['p.id', 'p.poll_language_id', 'greatest(' . implode(',', $select) . ') as date'])
            ->from('poll p')
            ->leftJoin('poll_comment pc', 'pc.poll_id=p.id')
            ->leftJoin('poll_rating_vote prv', 'prv.poll_id=p.id')
            ->orderBy(['date' => SORT_DESC ])
            ->asArray()->all();

        self::$data['poll']['links'] = array_fill_keys(array_keys(static::$subdomains), []);
        self::$data['poll']['maxDate'] = null;

        foreach ($list as $item) {
            $date = mb_substr($item['date'], 0, 10);
            if (self::$data['poll']['maxDate'] === null || self::$data['poll']['maxDate'] < $date) {
                self::$data['poll']['maxDate'] = $date;
            }

            if ( empty(self::$data['poll']['maxDates'][$item['poll_language_id']]) || self::$data['poll']['maxDates'][$item['poll_language_id']] < $date) {
                self::$data['poll']['maxDates'][$item['poll_language_id']] = $date;
            }
            if(!empty($item['poll_language_id']) && !empty($item['id'])){
                self::$data['poll']['links'][$item['poll_language_id']][] = [
                    'url' => 'https://'.static::$subdomains[$item['poll_language_id']] . '.' . SITE_DOMAIN . '/poll/' . $item['id'],
                    'lastmod' => $date,
                    'changefreq' => 'weekly',
                    'priority' =>  '0.7',
                ];
            }
        }

        self::$data['poll']['xml'] = [];
        foreach (static::$subdomains as $id => $subdomain) {
            self::$data['poll']['xml'][$id] = '<?xml version="1.0" encoding="UTF-8"?>';
            self::$data['poll']['xml'][$id] .= self::$br . '<?xml-stylesheet type="text/xsl" href="//' . $subdomain . '.' . self::$domen . '/main-sitemap.xsl"?>';
            self::$data['poll']['xml'][$id] .= self::$br . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . self::$br;
        }

        foreach (self::$data['poll']['links'] as $langId => $items) {
            foreach ($items as $item) {
                self::$data['poll']['xml'][$langId] .= self::getUrlBlock($item['url'], $item['lastmod'], $item['changefreq'], $item['priority']);
            }
        }

        foreach (static::$subdomains as $id => $subdomain) {
            self::$data['poll']['xml'][$id] .= '</urlset>';
        }

        return self::$data['poll']['xml'];
    }

    protected static function generateTag()
    {
        $list = Tag::find()
            ->select(['t.id', 't.language_id', 't.name AS idt', 'MAX(DATE_FORMAT(p.date_update, "%Y-%m-%d")) AS poll_date'])
            ->from('tag t')
            ->innerJoin('poll_tag p2t', 'p2t.tag_id = t.id')
            ->innerJoin('poll p', 'p2t.poll_id = p.id')
            ->where(['p.status' => Poll::POLL_STATUS_ACTIVE]) // тільки активні опитування
            ->groupBy(['t.id', 't.language_id', 't.name'])
            ->orderBy(['t.name' => SORT_ASC])
            ->asArray()->all();

        self::$data['tags']['links'] = array_fill_keys(array_keys(static::$subdomains), []);
        self::$data['tags']['maxDate'] = null;

        foreach ($list as $item) {
            $date = $item['poll_date'];

            if (self::$data['tags']['maxDate'] === null || self::$data['tags']['maxDate'] < $date) {
                self::$data['tags']['maxDate'] = $date;
            }

            if ( empty(self::$data['tags']['maxDates'][$item['language_id']]) || self::$data['tags']['maxDates'][$item['language_id']] < $date) {
                self::$data['tags']['maxDates'][$item['language_id']] = $date;
            }
            if(!empty($item['language_id']) && !empty($item['idt'])){
                self::$data['tags']['links'][$item['language_id']][] = [
                    'url' => 'https://'.static::$subdomains[$item['language_id']] . '.' . SITE_DOMAIN . '/tag/' . urlencode($item['idt']),
                    'lastmod' => $date,
                    'changefreq' => 'weekly',
                    'priority' =>  '0.7',
                ];
            }
        }

        self::$data['tags']['xml'] = [];
        foreach (static::$subdomains as $id => $subdomain) {
            self::$data['tags']['xml'][$id] = '<?xml version="1.0" encoding="UTF-8"?>';
            self::$data['tags']['xml'][$id] .= self::$br . '<?xml-stylesheet type="text/xsl" href="//' . $subdomain . '.' . self::$domen . '/main-sitemap.xsl"?>';
            self::$data['tags']['xml'][$id] .= self::$br . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . self::$br;
        }

        foreach (self::$data['tags']['links'] as $langId => $items) {
            foreach ($items as $item) {
                self::$data['tags']['xml'][$langId] .= self::getUrlBlock($item['url'], $item['lastmod'], $item['changefreq'], $item['priority']);
            }
        }

        foreach (static::$subdomains as $id => $subdomain) {
            self::$data['tags']['xml'][$id] .= self::$br.'</urlset>';
        }

        return self::$data['tags']['xml'];
    }

    public static function getUrlBlock($url, $lastmod, $changefreq = 'monthly', $priority = 0.3)
    {
        return "<url><loc>$url</loc><lastmod>$lastmod</lastmod><changefreq>$changefreq</changefreq><priority>$priority</priority></url>";
    }
}