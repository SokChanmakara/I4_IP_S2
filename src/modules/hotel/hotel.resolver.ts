import { Resolver, Query, Mutation, Args } from '@nestjs/graphql';

interface Hotel {
  id: string;
  name: string;
  address: string;
  phone: string;
}

@Resolver('Hotel')
export class HotelResolver {
  private hotels: Hotel[] = [];
  private idCounter = 1;

  @Query('hotels')
  getHotels(): Hotel[] {
    return this.hotels;
  }

  @Query('hotel')
  getHotel(@Args('id') id: string): Hotel | null {
    return this.hotels.find((h) => h.id === id) || null;
  }

  @Mutation('createHotel')
  createHotel(
    @Args('name') name: string,
    @Args('address') address: string,
    @Args('phone') phone: string,
  ): Hotel {
    const hotel: Hotel = {
      id: String(this.idCounter++),
      name,
      address,
      phone,
    };
    this.hotels.push(hotel);
    return hotel;
  }

  @Mutation('updateHotel')
  updateHotel(
    @Args('id') id: string,
    @Args('name') name?: string,
    @Args('address') address?: string,
    @Args('phone') phone?: string,
  ): Hotel | null {
    const hotel = this.hotels.find((h) => h.id === id);
    if (!hotel) return null;
    if (name !== undefined) hotel.name = name;
    if (address !== undefined) hotel.address = address;
    if (phone !== undefined) hotel.phone = phone;
    return hotel;
  }

  @Mutation('deleteHotel')
  deleteHotel(@Args('id') id: string): boolean {
    const idx = this.hotels.findIndex((h) => h.id === id);
    if (idx === -1) return false;
    this.hotels.splice(idx, 1);
    return true;
  }
}
