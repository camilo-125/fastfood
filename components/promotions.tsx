import { Card, CardContent } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Clock, Percent, Gift } from "lucide-react"

const promotions = [
  {
    icon: Clock,
    title: "Happy Hour",
    description: "2x1 en bebidas de 3pm a 6pm todos los días",
    cta: "Ver detalles",
  },
  {
    icon: Percent,
    title: "20% de descuento",
    description: "En tu primera orden con código BIENVENIDO",
    cta: "Usar código",
  },
  {
    icon: Gift,
    title: "Combo Familiar",
    description: "2 pizzas + alitas + bebidas por solo $39.99",
    cta: "Ordenar ahora",
  },
]

export function Promotions() {
  return (
    <section id="promociones" className="bg-muted py-20 md:py-32">
      <div className="container mx-auto px-4">
        <div className="mb-12 flex flex-col gap-4 text-center">
          <h2 className="font-sans text-4xl font-bold text-balance text-foreground md:text-5xl">Ofertas especiales</h2>
          <p className="mx-auto max-w-2xl font-sans text-lg leading-relaxed text-muted-foreground">
            Aprovecha nuestras promociones y ahorra en tus platillos favoritos
          </p>
        </div>

        <div className="grid gap-8 md:grid-cols-3">
          {promotions.map((promo) => (
            <Card key={promo.title} className="border-2 transition-all hover:border-primary hover:shadow-lg">
              <CardContent className="flex flex-col items-center gap-6 p-8 text-center">
                <div className="flex h-16 w-16 items-center justify-center rounded-full bg-primary/10">
                  <promo.icon className="h-8 w-8 text-primary" />
                </div>
                <div>
                  <h3 className="mb-2 font-sans text-2xl font-bold text-card-foreground">{promo.title}</h3>
                  <p className="font-sans leading-relaxed text-muted-foreground">{promo.description}</p>
                </div>
                <Button className="w-full">{promo.cta}</Button>
              </CardContent>
            </Card>
          ))}
        </div>
      </div>
    </section>
  )
}
