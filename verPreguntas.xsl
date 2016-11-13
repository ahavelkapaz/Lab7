

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:template match="/assessmentItems">
<html>

<body>
    <table border="1">
      <tr bgcolor="#9acd32">
        <th>Pregunta</th>
        <th>Complejidad</th>
		<th>Tema</th>
      </tr>
      <xsl:for-each select="assessmentItem">
        <tr>
          <td><xsl:value-of select="itemBody/p"/></td>
		  
          <td><xsl:value-of select="@complexity"/></td>
		  <td><xsl:value-of select="@subject"/></td>
        </tr>
      </xsl:for-each>
    </table>
	</body>
</html>
</xsl:template>

</xsl:stylesheet>