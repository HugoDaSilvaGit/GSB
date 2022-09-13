<form class="has-text-left" action="" method="POST">
                                                    {% if unefichefrais.lignesfraishorsforfait is defined %}
                                                        {% for unelignefraishorsforfait in unefichefrais.lignesfraishorsforfait %}
                                                            {{ unelignefraishorsforfait.libelle }}
                                                            - {{ unelignefraishorsforfait.montant }} €
                                                        {% endfor %}
                                                    {% else %}
                                                        - 0 €
                                                    {% endif %}
                                                </form>