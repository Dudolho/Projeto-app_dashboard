$(document).ready(() => {
	
    $('#documentacao').on('click', ()=> {
        $('#pagina').load('documentacao.html')
    })

    $('#suporte').on('click', ()=> {
        $('#pagina').load('suporte.html')
    })
    
    //ajax 
    $('#competencia').on('change', e=> {
        let competencia = $(e.target).val();

        $.ajax({
            //método, url, dados, sucesso, erro
            type: 'GET',
            url: 'app.php',
            data: `competencia=${competencia}`,
            dataType: 'json',
            success: dados => {
                $('#numero_vendas').html(dados.numeroVendas)
                $('#total_vendas').html(dados.totalVendas)
                $('#clientes_ativos').html(dados.clientesAtivos)
                $('#clientes_inativos').html(dados.clientesInativos)
                $('#reclamacao').html(dados.totalReclamacoes)
                $('#sugestoes').html(dados.totalElogios)
                $('#elogios').html(dados.totalSugestoes)
                $('#despesas').html(dados.totalDespesas)
            },
            error: erro => {
                console.log(erro)
            }
        })
    })

})