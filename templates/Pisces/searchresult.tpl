[searchposts]
[fullresult]
<div class="base shortstory">
	<div class="dpad">
		<h3 class="btl">[full-link]{title}[/full-link]</h3>
		<p class="binfo small">�����: {author} �� [day-news]{date}[/day-news], ����������: {views}</p>
		<div class="maincont">
			<span class="argcoms">[com-link]<b>{comments-num}</b>[/com-link]</span>
			{short-story}
			<div class="clr"></div>
		</div>
		[edit-date]<p class="editdate small"><i>�������: <b>{editor}</b>[edit-reason] �� �������: {edit-reason}[/edit-reason]</i></p>[/edit-date]
		<div class="mlink"><div class="mlink">
			<span class="argmore">[full-link]<b>���������</b>[/full-link]</span>
			[not-group=5]
			<ul class="isicons reset">
				<li>[edit]<img src="{THEME}/dleimages/editstore.png" title="�������������" alt="�������������" />[/edit]</li>
				<li>{favorites}</li>
			</ul>
			[/not-group]
			[rating]<div class="rate">{rating}</div>[/rating]
		</div></div>
		<p class="argcat small">���������: {link-category}</p>
	</div>
</div>
<div class="bsep">&nbsp;</div>
[/fullresult]
[shortresult]
<div class="dpad searchitem">
	<h3>[full-link]{title}[/full-link]</h3>
	<b>[day-news]{date}[/day-news]</b> | {link-category} | �����: {author}
</div>
[/shortresult]
[/searchposts]
[searchcomments]
[fullresult]
	<div class="bcomment">
		<div class="lcol">
			<span class="thide arcom">&lt;</span>
			<div class="avatar">
				<img src="{foto}" alt=""/>
			</div>
		</div>
		<div class="rcol">
			<div class="dpad dtop">
				<span>{result-date}</span>
				<h3>{result-author}</h3>
			</div>
			<div class="dpad cominfo">
				<ul class="reset small">
					<li>�����������: {registration}</li>
				</ul>
				<span class="dleft">&nbsp;</span>
			</div>
			<div class="dpad dcont">
				<h3 style="margin-bottom: 0.4em;">[result-link]{result-title}[/result-link]</h3>
				{result-text}
				<br clear="all" />
			</div>
			[not-group=5]
			<div class="dpad comedit">
				<ul class="reset small">
					<li>[com-del]�������[/com-del]</li>
					<li>[com-edit]��������[/com-edit]</li>
				</ul>
			</div>
			[/not-group]					
		</div>
		<div class="clr"></div>
	</div>
[/fullresult]
[shortresult]
<div class="dpad searchitem">
	<h3 style="margin-bottom: 0.4em;">{news_title}</h3>
	<b>{date}</b> | �����: {author}
</div>
[/shortresult]
[/searchcomments]