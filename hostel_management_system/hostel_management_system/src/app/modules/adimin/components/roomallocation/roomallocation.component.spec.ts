import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RoomallocationComponent } from './roomallocation.component';

describe('RoomallocationComponent', () => {
  let component: RoomallocationComponent;
  let fixture: ComponentFixture<RoomallocationComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ RoomallocationComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(RoomallocationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
