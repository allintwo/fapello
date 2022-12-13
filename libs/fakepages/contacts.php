<?php

$admin_mail = 'admin@'.$_SERVER['HTTP_HOST'];


echo <<<sdhfdsufgdsiufgdsgufspdpgisdfiogfdoi
<div>
			<div class="lg:p-12 max-w-md max-w-xl lg:my-0 my-12 mx-auto p-6 space-y-">
				<h1 class="lg:text-3xl text-xl font-semibold mb-6"> Contacts / DMCA</h1>

				<button onclick="location.href='mailto:{$admin_mail}'" class="bg-gradient-to-br from-pink-500 p-3 rounded-md text-white text-xl to-red-400 w-full my-5">$admin_mail</button>
			</div>
</div>

sdhfdsufgdsiufgdsgufspdpgisdfiogfdoi;
