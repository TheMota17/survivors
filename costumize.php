<div class='flex j-c mt10'>
	<div class='cost-zag backgr2 flex j-c pt5 pb5'>
		Настройте своего персонажа
	</div>
</div>

<div class='flex j-c mt10'>
	<div class='cost-message backgr2 flex j-c ai-c pt5 pb5'>
		<div class='wdth90 flex j-c ai-c'>
			<img src='/img/icons/mess.png' class='mr5'>
			<span class='mess fnt13'>След. настройка будет доступна за игровую валюту</span>
		</div>
	</div>
</div>

<div class='flex j-c mt10'>
	<div class='pers-maneken backgr2 flex j-c ai-c fl-di-co'>
		<div class='cost-elem-name flex j-c mt5 none' id='style_elem_name'>
			<span class='mr5' id='elem_name'>Борода</span>
			<span id='elem_colvo'>0</span>/<span id='elem_max'>0</span>
		</div>
		<div class='flex j-c ai-c pt5 pb5'>
			<div class='flex j-c fl-di-co'>
				<button class='cost-prevnext-btn mr5' id='prev-hair'> ◄ </button>
				<button class='cost-prevnext-btn mt10 mr5' id='prev-beard'> ◄ </button>
				<button class='cost-prevnext-btn mt10 mr5' id='prev-cloth'> ◄ </button>
				<button class='cost-prevnext-btn mt10 mr5' id='prev-pants'> ◄ </button>
				<button class='cost-prevnext-btn mt10 mr5' id='prev-fwear'> ◄ </button>
			</div>

			<div class='maneken relative flex j-c ai-c'>
				<div class='hair1'  id='hair'></div>
				<div class='beard1' id='beard'></div>
				<div class='cloth1' id='cloth'></div>
				<div class='pants1' id='pants'></div>
				<div class='fwear1' id='fwear'></div>

				<img class='man' src='/img/man/man.png'>

				<div class='maneken-shadow'></div>
			</div>

			<div class='flex j-c fl-di-co'>
				<button class='cost-prevnext-btn ml5' id='next-hair'> ► </button>
				<button class='cost-prevnext-btn mt10 ml5' id='next-beard'> ► </button> 
				<button class='cost-prevnext-btn mt10 ml5' id='next-cloth'> ► </button> 
				<button class='cost-prevnext-btn mt10 ml5' id='next-pants'> ► </button> 
				<button class='cost-prevnext-btn mt10 ml5' id='next-fwear'> ► </button> 
			</div>
		</div>
	</div>
</div>

<div class='flex j-c mt10'>
	<div class='cost-desicions backgr2 flex j-c pt5 pb5'>
		<div class='flex j-c fl-di-co' id='ready-hide'>
			<button class='cost-ready-btn' id='ready'>Готово</button>
		</div>
		<div class='flex j-c fl-di-co none' id='confirm-hide'>
			<div class='flex j-c'>
				Вы уверены?
			</div>
			<div class='flex j-c mt5'>
				<button class='cost-confirm-btn mr10' id='costumize_no'>Нет</button> 
			    <button class='cost-confirm-btn' id='costumize_yes'>Да</button>
			</div>
		</div>
	</div>
</div>