<table width="100%">
    <!--<thead>
    <tr>
        <td>Reporte:</td>
    </tr>
    </thead>-->
    <tbody>
        {reportes}
        <tr>
            <td class="col-lg-6"><a href="{base_url}financiamiento/reportes/get_report/{idkpi}">{title}</a></td><td class="col-lg-4"><a class="btn btn-primary btn-xs" href="{base_url}financiamiento/reportes/get_report/{idkpi}/true">Exportar</a></td>
        </tr>
        {/reportes}
    </tbody>
</table>