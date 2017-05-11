<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$config['pagination_common_list'] = array(
	'per_page' => 10,
	'page_query_string' => false,
	'use_page_numbers' => true,
	'query_string_segment' => 'page',
	'num_links' => 3,
	#'first_tag_open' => '<a href="javascript:;" class="laypage_prev">', //第一个链接的起始标签
	#'first_tag_close' => '</a>',
	#'full_tag_open' => '<div class="pageLink mgT20 mgAuto">',  //包围分页的标签
	#'full_tag_close' => '</div>',
	'prev_link' => '上一页',
	'next_link' => '下一页',
	'cur_tag_open' => '<span class="laypage_curr">',
	'cur_tag_close' => '</span>',
	'last_link' => '末页',
	'first_link' => '首页'
);

