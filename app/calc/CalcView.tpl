{extends file=$conf->root_path|cat:"/templates/main.tpl"}

{block name=footer}Anna Woronko <br> Zajęcia 5. Kontroler główny.{/block}

{block name=content}

<h2 class="content-head is-center">KALKULATOR KREDYTOWY</h2>

<div class="pure-g">
<div class="l-box-lrg pure-u-1 pure-u-med-2-5">
	<form class="pure-form pure-form-stacked" action="{$conf->action_root}calcCompute" method="post">
		<fieldset>
                        <div class="row">
                                <div class="col-sm-4">
                                    <label for="kw"> Kwota kredytu: </label>
                                    <input id="kw" class="form-control" type="text" placeholder="Kwota kredytu" name="kw" value="{$form->kwota}" /><br />
                                </div>
                                <div class="col-sm-4">
                                    <label for="ok"> Okres (w latach): </label>
                                    <input id="ok" class="form-control" type="text" placeholder="Okres (w latach)" name="ok" value="{$form->okres}" /><br />
                                </div>
                                <div class="col-sm-4">
                                    <label for="op"> Oprocentowanie: </label>
                                    <input id="op" class="form-control" type="text" placeholder="Oprocentowanie" name="op" value="{$form->oprocentowanie}" /><br />
                                </div>
                        </div>
                        <br/>
                        <div class="row">
                                <div class="col-sm-12 text-right">
                                        <button type="submit" class="pure-button">Oblicz</button>
                                </div>
                        </div> 
		</fieldset>
	</form>
</div>

<div class="l-box-lrg pure-u-1 pure-u-med-3-5">

{* wyświeltenie listy błędów, jeśli istnieją *}
{if $msgs->isError()}
	<h4>Wystąpiły błędy: </h4>
	<ol class="err">
	{foreach $msgs->getErrors() as $err}
	{strip}
		<li>{$err}</li>
	{/strip}
	{/foreach}
	</ol>
{/if}

{* wyświeltenie listy informacji, jeśli istnieją *}
{if $msgs->isInfo()}
	<h4>Informacje: </h4>
	<ol class="inf">
	{foreach $msgs->getInfos() as $inf}
	{strip}
		<li>{$inf}</li>
	{/strip}
	{/foreach}
	</ol>
{/if}

{if isset($res->result)}
	<h4>Miesięczna rata kredytu wyniesie:</h4>
	<p class="res">
            {$res->result|string_format:"%.2f"}{" zł"}
	</p>
{/if}

</div>
</div>

{/block}