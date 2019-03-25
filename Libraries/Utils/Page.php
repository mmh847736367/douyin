<?php
/**
 * Created by PhpStorm.
 * User: jiyun
 * Date: 2018/8/17
 * Time: 12:27
 */
namespace Libraries\Utils;

class Page
{
    public  $uri;
    public  $cp;
    public  $lp;
    public  $formate;
    
    public function __construct($uri, $cp, $lp,$formate='cate')
    {
        $this->uri = $uri;
        $this->cp = $cp;
        $this->lp = $lp;
        if($lp == false) {
            $this->lp = 1;
        }
        $this->formate = $formate;
    }

    public function init()
    {
        if($this->cp > $this->lp) {
            $this->cp = $this->lp;
        }
        $prev = $this->cp - 1 ;
        if($prev <= 0) {
            $prev = 1;
        }
        $next = $this->cp + 1 ;
        if($next > $this->lp) {
            $next = $this->lp;
        }
        $alist = '';
        if($this->cp<=3) {
            $page_start = 1;
        } elseif(3<$this->cp && $this->cp< $this->lp-2) {
            $page_start = $this->cp-2;
        } else{
            $page_start = $this->lp-4;
        }

        if($this->formate == 'cate') {
            for ($i = $page_start; $i<$page_start+5; $i++) {
                if($i == $this->cp) {
                    if( $i == 1) {
                        $alist .= "<a href='{$this->uri}/' class='current'>$i</a>";
                    } else{
                        $alist .= "<a href='{$this->uri}/{$i}.html' class='current'>$i</a>";
                    }
                }else {
                    if($i == 1) {
                        $alist .= "<a href='{$this->uri}/'>$i</a>";
                    } else {
                        $alist .= "<a href='{$this->uri}/{$i}.html' >$i</a>";
                    }
                }

                if($i == $this->lp) {
                    break;
                }
            }

            if($this->cp >= 2) {
                if( $prev ==1 ) {
                    $page_pre = "<a href='{$this->uri}/'> < </a>";
                } else {
                    $page_pre = "<a href='{$this->uri}/{$prev}.html'> < </a>";
                }
            } else {
                $page_pre = '';
            }

            if($this->cp <= $this->lp -2) {
                $page_next = "<a href='{$this->uri}/{$next}.html'> > </a>";
            }else {
                $page_next = '';
            }

            if($this->cp > 2) {
                $ahome = "<a href='{$this->uri}/'>首页</a>";
            } else {
                $ahome = '';
            }

            if($this->cp < $this->lp -2) {
                if($this->lp ==1) {
                    $alast = "<a href='{$this->uri}/'>尾页</a>";
                } else {
                    $alast = "<a href='{$this->uri}/{$this->lp}.html'>尾页</a>";
                }

            }else {
                $alast = '';
            }
            $html = $ahome.$page_pre.$alist.$page_next.$alast;
        } else {
            for ($i = $page_start; $i<$page_start+5; $i++) {
                if($i == $this->cp) {
                    if( $i == 1) {
                        $alist .= "<a href='{$this->uri}' class='current'>$i</a>";
                    } else{
                        $alist .= "<a href='{$this->uri}&page=$i' class='current'>$i</a>";
                    }
                }else {
                    if($i == 1) {
                        $alist .= "<a href='{$this->uri}'>$i</a>";
                    } else {
                        $alist .= "<a href='{$this->uri}&page=$i' >$i</a>";
                    }
                }

                if($i == $this->lp) {
                    break;
                }
            }

            if($this->cp >= 2) {
                if( $prev ==1 ) {
                    $page_pre = "<a href='{$this->uri}'> < </a>";
                } else {
                    $page_pre = "<a href='{$this->uri}&page=$i'> < </a>";
                }
            } else {
                $page_pre = '';
            }

            if($this->cp <= $this->lp -2) {
                $page_next = "<a href='{$this->uri}&page=$next'> > </a>";
            }else {
                $page_next = '';
            }

            if($this->cp > 2) {
                $ahome = "<a href='{$this->uri}'>首页</a>";
            } else {
                $ahome = '';
            }

            if($this->cp < $this->lp -2) {
                if($this->lp ==1) {
                    $alast = "<a href='{$this->uri}'>尾页</a>";
                } else {
                    $alast = "<a href='{$this->uri}&page={$this->lp}'>尾页</a>";
                }

            }else {
                $alast = '';
            }
            $html = $ahome.$page_pre.$alist.$page_next.$alast;
        }

        return $html;
    }
}